<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Environnement désert - Tempête</title>
    <meta name="description" content="Environnement desert avec tempête" />
    <script src="https://aframe.io/releases/1.6.0/aframe.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aframe-environment-component@1.3.7/dist/aframe-environment-component.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/c-frame/aframe-particle-system-component@1.0.x/dist/aframe-particle-system-component.min.js"></script>

    <script>
        /**
         * Composant de téléportation custom compatible A-Frame 1.6.0 et Oculus Rift CV1
         */
        AFRAME.registerComponent('teleport-controls-custom', {
            schema: {
                cameraRig: { type: 'selector', default: '#rig' },
                teleportOrigin: { type: 'selector', default: '#camera' },
                collisionEntities: { type: 'string', default: '.teleportable' },
                button: { type: 'string', default: 'trigger' },
                curveShootingSpeed: { type: 'number', default: 10 },
                curveNumberPoints: { type: 'number', default: 30 },
                curveHitColor: { type: 'color', default: '#00ff00' },
                curveMissColor: { type: 'color', default: '#ff0000' },
                landingMaxAngle: { type: 'number', default: 45 }
            },

            init: function () {
                this.active = false;
                this.hitPoint = null;
                this.hit = false;
                this.raycaster = new THREE.Raycaster();
                this.direction = new THREE.Vector3();
                this.gravity = new THREE.Vector3(0, -9.8, 0);

                this.createCurveLine();
                this.createHitMarker();

                // Événements pour Oculus Touch
                this.el.addEventListener('triggerdown', this.onButtonDown.bind(this));
                this.el.addEventListener('triggerup', this.onButtonUp.bind(this));
                
                // Événements WebXR génériques
                this.el.addEventListener('selectstart', this.onButtonDown.bind(this));
                this.el.addEventListener('selectend', this.onButtonUp.bind(this));
                
                console.log('Teleport controls initialized');
            },

            createCurveLine: function () {
                const geometry = new THREE.BufferGeometry();
                const positions = new Float32Array(this.data.curveNumberPoints * 3);
                geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));

                const material = new THREE.LineBasicMaterial({
                    color: this.data.curveMissColor,
                    linewidth: 2
                });

                this.curveLine = new THREE.Line(geometry, material);
                this.curveLine.visible = false;
                this.curveLine.frustumCulled = false;
                this.el.sceneEl.object3D.add(this.curveLine);
            },

            createHitMarker: function () {
                this.hitMarker = document.createElement('a-entity');

                const ring = document.createElement('a-ring');
                ring.setAttribute('color', this.data.curveHitColor);
                ring.setAttribute('radius-inner', '0.4');
                ring.setAttribute('radius-outer', '0.5');
                ring.setAttribute('rotation', '-90 0 0');
                this.hitMarker.appendChild(ring);

                const circle = document.createElement('a-circle');
                circle.setAttribute('color', this.data.curveHitColor);
                circle.setAttribute('radius', '0.2');
                circle.setAttribute('rotation', '-90 0 0');
                circle.setAttribute('opacity', '0.5');
                this.hitMarker.appendChild(circle);

                this.hitMarker.setAttribute('visible', 'false');
                this.el.sceneEl.appendChild(this.hitMarker);
            },

            onButtonDown: function () {
                this.active = true;
                this.curveLine.visible = true;
            },

            onButtonUp: function () {
                if (this.active && this.hit && this.hitPoint) {
                    this.teleport();
                }
                this.active = false;
                this.curveLine.visible = false;
                this.hitMarker.setAttribute('visible', 'false');
            },

            teleport: function () {
                const rig = this.data.cameraRig;
                if (!rig) return;

                const camera = this.data.teleportOrigin;
                let cameraOffset = new THREE.Vector3();

                if (camera) {
                    camera.object3D.getWorldPosition(cameraOffset);
                    const rigPos = new THREE.Vector3();
                    rig.object3D.getWorldPosition(rigPos);
                    cameraOffset.sub(rigPos);
                    cameraOffset.y = 0;
                }

                rig.object3D.position.x = this.hitPoint.x - cameraOffset.x;
                rig.object3D.position.z = this.hitPoint.z - cameraOffset.z;
            },

            tick: function () {
                if (!this.active) return;
                this.updateCurve();
            },

            updateCurve: function () {
                const controllerObj = this.el.object3D;
                const p0 = new THREE.Vector3();
                controllerObj.getWorldPosition(p0);

                const quaternion = new THREE.Quaternion();
                controllerObj.getWorldQuaternion(quaternion);
                const v0 = new THREE.Vector3(0, 0, -1);
                v0.applyQuaternion(quaternion);
                v0.multiplyScalar(this.data.curveShootingSpeed);

                const positions = this.curveLine.geometry.attributes.position.array;
                let lastPoint = p0.clone();
                this.hit = false;
                this.hitPoint = null;

                const collisionEntities = document.querySelectorAll(this.data.collisionEntities);
                const meshes = [];
                collisionEntities.forEach(entity => {
                    const mesh = entity.getObject3D('mesh');
                    if (mesh) meshes.push(mesh);
                });

                for (let i = 0; i < this.data.curveNumberPoints; i++) {
                    const t = i / 10;

                    const nextPoint = new THREE.Vector3();
                    nextPoint.copy(p0);
                    nextPoint.add(v0.clone().multiplyScalar(t));
                    nextPoint.add(this.gravity.clone().multiplyScalar(0.5 * t * t));

                    positions[i * 3] = nextPoint.x;
                    positions[i * 3 + 1] = nextPoint.y;
                    positions[i * 3 + 2] = nextPoint.z;

                    if (!this.hit && meshes.length > 0) {
                        const direction = nextPoint.clone().sub(lastPoint);
                        const distance = direction.length();
                        direction.normalize();

                        this.raycaster.set(lastPoint, direction);
                        this.raycaster.far = distance;

                        for (let mesh of meshes) {
                            const intersects = this.raycaster.intersectObject(mesh, true);
                            if (intersects.length > 0) {
                                const intersect = intersects[0];

                                const normal = intersect.face.normal.clone();
                                normal.transformDirection(intersect.object.matrixWorld);
                                const up = new THREE.Vector3(0, 1, 0);
                                const angle = THREE.MathUtils.radToDeg(normal.angleTo(up));

                                if (angle <= this.data.landingMaxAngle) {
                                    this.hit = true;
                                    this.hitPoint = intersect.point.clone();

                                    for (let j = i; j < this.data.curveNumberPoints; j++) {
                                        positions[j * 3] = this.hitPoint.x;
                                        positions[j * 3 + 1] = this.hitPoint.y;
                                        positions[j * 3 + 2] = this.hitPoint.z;
                                    }
                                    break;
                                }
                            }
                        }
                    }

                    lastPoint.copy(nextPoint);
                }

                this.curveLine.geometry.attributes.position.needsUpdate = true;
                this.curveLine.material.color.set(this.hit ? this.data.curveHitColor : this.data.curveMissColor);

                if (this.hit && this.hitPoint) {
                    this.hitMarker.setAttribute('position', this.hitPoint);
                    this.hitMarker.setAttribute('visible', 'true');
                } else {
                    this.hitMarker.setAttribute('visible', 'false');
                }
            },

            remove: function () {
                if (this.curveLine) {
                    this.el.sceneEl.object3D.remove(this.curveLine);
                }
                if (this.hitMarker) {
                    this.hitMarker.parentNode.removeChild(this.hitMarker);
                }
            }
        });

        // Composant pour animer le chameau
        AFRAME.registerComponent('camel-animator', {
            init: function () {
                this.mixer = null;
                this.clock = new THREE.Clock();

                this.el.addEventListener('model-loaded', () => {
                    const model = this.el.getObject3D('mesh');
                    if (model && model.animations && model.animations.length > 0) {
                        this.mixer = new THREE.AnimationMixer(model);
                        let animToPlay = model.animations.find(a => a.name === 'Armature|WalkCycle');
                        if (!animToPlay) animToPlay = model.animations[0];
                        const action = this.mixer.clipAction(animToPlay);
                        action.play();
                    }
                });
            },

            tick: function () {
                if (this.mixer) this.mixer.update(this.clock.getDelta());
            }
        });

        // Composant pour faire marcher le chameau
        AFRAME.registerComponent('camel-walker', {
            schema: {
                distance: { type: 'number', default: 15 },
                speed: { type: 'number', default: 2 }
            },

            init: function () {
                this.direction = 1;
                this.startZ = this.el.object3D.position.z;
            },

            tick: function (time, deltaTime) {
                const position = this.el.object3D.position;
                const rotation = this.el.object3D.rotation;

                const moveSpeed = this.data.speed * (deltaTime / 1000) * this.direction;
                position.z += moveSpeed;

                if (this.direction === 1 && position.z >= this.startZ + this.data.distance) {
                    this.direction = -1;
                    rotation.y = 0;
                } else if (this.direction === -1 && position.z <= this.startZ - this.data.distance) {
                    this.direction = 1;
                    rotation.y = Math.PI;
                }
            }
        });

        // Composant pour animer l'araignée
        AFRAME.registerComponent('spider-animator', {
            init: function () {
                this.mixer = null;
                this.clock = new THREE.Clock();

                this.el.addEventListener('model-loaded', () => {
                    const model = this.el.getObject3D('mesh');
                    if (model && model.animations && model.animations.length > 0) {
                        this.mixer = new THREE.AnimationMixer(model);
                        // Essayer l'animation 1, sinon prendre la première
                        let animIndex = model.animations.length > 1 ? 1 : 0;
                        const action = this.mixer.clipAction(model.animations[animIndex]);
                        action.play();
                    }
                });
            },

            tick: function () {
                if (this.mixer) this.mixer.update(this.clock.getDelta());
            }
        });

        // Composant pour faire marcher l'araignée
        AFRAME.registerComponent('spider-walker', {
            schema: {
                speed: { type: 'number', default: 0.5 },
                radius: { type: 'number', default: 10 },
                changeInterval: { type: 'number', default: 3000 }
            },

            init: function () {
                this.startPos = this.el.object3D.position.clone();
                this.targetAngle = Math.random() * Math.PI * 2;
                this.lastChange = 0;
                this.el.object3D.rotation.y = this.targetAngle - Math.PI;
                this.paused = false;
            },

            tick: function (time, deltaTime) {
                // Ne pas bouger si en pause (attrapé)
                if (this.paused) return;
                
                const position = this.el.object3D.position;
                const rotation = this.el.object3D.rotation;

                if (time - this.lastChange > this.data.changeInterval) {
                    this.targetAngle = Math.random() * Math.PI * 2;
                    this.lastChange = time;
                }

                let angleDiff = this.targetAngle - rotation.y;
                while (angleDiff > Math.PI) angleDiff -= Math.PI * 2;
                while (angleDiff < -Math.PI) angleDiff += Math.PI * 2;

                rotation.y += angleDiff * 0.05;

                const moveSpeed = this.data.speed * (deltaTime / 1000);
                position.x += Math.sin(rotation.y + Math.PI) * moveSpeed;
                position.z += Math.cos(rotation.y + Math.PI) * moveSpeed;

                const distFromStart = Math.sqrt(
                    Math.pow(position.x - this.startPos.x, 2) +
                    Math.pow(position.z - this.startPos.z, 2)
                );

                if (distFromStart > this.data.radius) {
                    const angleToCenter = Math.atan2(
                        this.startPos.x - position.x,
                        this.startPos.z - position.z
                    );
                    this.targetAngle = angleToCenter + Math.PI;
                }
            }
        });

        /**
         * Composant grabbable - rend un objet attrapable
         */
        AFRAME.registerComponent('grabbable', {
            schema: {
                enabled: { type: 'boolean', default: true }
            },

            init: function () {
                this.isGrabbed = false;
                this.grabber = null;
                this.originalParent = this.el.parentNode;
                this.originalPosition = new THREE.Vector3();
                this.originalRotation = new THREE.Euler();
                
                // Émettre un événement quand l'objet est survolé
                this.el.addEventListener('raycaster-intersected', () => {
                    this.el.emit('hovered');
                });
                
                this.el.addEventListener('raycaster-intersected-cleared', () => {
                    this.el.emit('unhovered');
                });
            },

            grab: function (hand) {
                if (!this.data.enabled || this.isGrabbed) return;
                
                this.isGrabbed = true;
                this.grabber = hand;
                
                // Sauvegarder la position/rotation mondiale
                this.el.object3D.getWorldPosition(this.originalPosition);
                this.originalRotation.copy(this.el.object3D.rotation);
                
                // Attacher à la main
                hand.object3D.attach(this.el.object3D);
                
                // Émettre l'événement grab
                this.el.emit('grabbed', { hand: hand });
            },

            release: function () {
                if (!this.isGrabbed) return;
                
                // Récupérer la position mondiale actuelle
                const worldPos = new THREE.Vector3();
                this.el.object3D.getWorldPosition(worldPos);
                
                // Détacher de la main et rattacher à la scène
                this.el.sceneEl.object3D.attach(this.el.object3D);
                
                this.isGrabbed = false;
                this.grabber = null;
                
                // Émettre l'événement release
                this.el.emit('released');
            }
        });

        /**
         * Composant grab-controls pour Oculus Rift CV1
         * Bouton: GRIP (bouton latéral) pour attraper
         */
        AFRAME.registerComponent('grab-controls', {
            schema: {
                hand: { type: 'string', default: 'right' },
                grabDistance: { type: 'number', default: 3 }
            },

            init: function () {
                this.grabbedObject = null;
                this.raycaster = new THREE.Raycaster();
                this.raycaster.far = this.data.grabDistance;
                this.hoveredObject = null;
                this.isGrabbing = false;
                this.controllerConnected = false;
                
                // Créer le laser visuel
                this.createLaser();
                
                // Attendre que le contrôleur soit connecté
                this.el.addEventListener('controllerconnected', (e) => {
                    console.log('Controller connected:', e.detail.name);
                    this.controllerConnected = true;
                    this.setupControllerEvents();
                });
                
                // Setup immédiat au cas où
                this.setupControllerEvents();
                
                console.log('Grab controls init for', this.data.hand);
            },
            
            setupControllerEvents: function() {
                // Événements Oculus Touch pour GRIP
                this.el.addEventListener('gripdown', this.onGrabStart.bind(this));
                this.el.addEventListener('gripup', this.onGrabEnd.bind(this));
                
                // Alternative: utiliser trigger si grip ne marche pas
                // this.el.addEventListener('triggerdown', this.onGrabStart.bind(this));
                // this.el.addEventListener('triggerup', this.onGrabEnd.bind(this));
                
                // Événements génériques WebXR
                this.el.addEventListener('selectstart', this.onGrabStart.bind(this));
                this.el.addEventListener('selectend', this.onGrabEnd.bind(this));
                this.el.addEventListener('squeezestart', this.onGrabStart.bind(this));
                this.el.addEventListener('squeezeend', this.onGrabEnd.bind(this));
            },

            createLaser: function () {
                // Conteneur pour le laser
                this.laserContainer = document.createElement('a-entity');
                
                // Ligne du laser
                const laserLine = document.createElement('a-entity');
                laserLine.setAttribute('geometry', {
                    primitive: 'cylinder',
                    radius: 0.002,
                    height: this.data.grabDistance,
                    segmentsRadial: 6
                });
                laserLine.setAttribute('material', {
                    color: '#00aaff',
                    opacity: 0.6,
                    transparent: true
                });
                laserLine.setAttribute('position', '0 0 -' + (this.data.grabDistance / 2));
                laserLine.setAttribute('rotation', '90 0 0');
                laserLine.className = 'laser-line';
                this.laserContainer.appendChild(laserLine);
                this.laserLine = laserLine;
                
                // Point de visée
                const hitPoint = document.createElement('a-sphere');
                hitPoint.setAttribute('radius', '0.02');
                hitPoint.setAttribute('color', '#00aaff');
                hitPoint.setAttribute('position', '0 0 -' + this.data.grabDistance);
                hitPoint.className = 'laser-hit';
                this.laserContainer.appendChild(hitPoint);
                this.laserHitPoint = hitPoint;
                
                this.el.appendChild(this.laserContainer);
            },
            
            updateLaser: function(distance, isHovering) {
                const color = isHovering ? '#00ff00' : '#00aaff';
                const dist = distance || this.data.grabDistance;
                
                this.laserLine.setAttribute('geometry', 'height', dist);
                this.laserLine.setAttribute('position', '0 0 -' + (dist / 2));
                this.laserLine.setAttribute('material', 'color', color);
                this.laserHitPoint.setAttribute('position', '0 0 -' + dist);
                this.laserHitPoint.setAttribute('color', color);
            },

            onGrabStart: function (evt) {
                if (this.isGrabbing) return;
                
                console.log('GRAB START:', evt.type, 'hovering:', !!this.hoveredObject);
                
                if (this.hoveredObject) {
                    const grabbable = this.hoveredObject.components.grabbable;
                    if (grabbable && !grabbable.isGrabbed) {
                        grabbable.grab(this.el);
                        this.grabbedObject = this.hoveredObject;
                        this.isGrabbing = true;
                        this.laserContainer.setAttribute('visible', false);
                        console.log('Object grabbed!');
                    }
                }
            },

            onGrabEnd: function (evt) {
                if (!this.isGrabbing) return;
                
                console.log('GRAB END:', evt.type);
                
                if (this.grabbedObject) {
                    const grabbable = this.grabbedObject.components.grabbable;
                    if (grabbable) {
                        grabbable.release();
                    }
                    this.grabbedObject = null;
                }
                
                this.isGrabbing = false;
                this.laserContainer.setAttribute('visible', true);
            },

            tick: function () {
                if (this.isGrabbing) return;
                
                // Position et direction du contrôleur
                const controllerPos = new THREE.Vector3();
                const controllerDir = new THREE.Vector3(0, 0, -1);
                
                this.el.object3D.getWorldPosition(controllerPos);
                const quaternion = new THREE.Quaternion();
                this.el.object3D.getWorldQuaternion(quaternion);
                controllerDir.applyQuaternion(quaternion);
                
                this.raycaster.set(controllerPos, controllerDir.normalize());
                
                // Chercher les objets grabbable
                const grabbables = document.querySelectorAll('[grabbable]');
                let closestHit = null;
                let closestDist = Infinity;
                
                grabbables.forEach(entity => {
                    const mesh = entity.getObject3D('mesh');
                    if (!mesh) return;
                    
                    const intersects = this.raycaster.intersectObject(mesh, true);
                    if (intersects.length > 0 && intersects[0].distance < closestDist) {
                        closestDist = intersects[0].distance;
                        closestHit = { entity: entity, distance: closestDist };
                    }
                });
                
                // Mettre à jour
                if (closestHit && closestHit.distance <= this.data.grabDistance) {
                    this.hoveredObject = closestHit.entity;
                    this.updateLaser(closestHit.distance, true);
                } else {
                    this.hoveredObject = null;
                    this.updateLaser(this.data.grabDistance, false);
                }
            }
        });

        /**
         * Modification du spider-walker pour s'arrêter quand attrapé
         */
        AFRAME.registerComponent('stoppable-on-grab', {
            init: function () {
                this.el.addEventListener('grabbed', () => {
                    const walker = this.el.components['spider-walker'];
                    if (walker) walker.paused = true;
                });
                
                this.el.addEventListener('released', () => {
                    const walker = this.el.components['spider-walker'];
                    if (walker) {
                        walker.paused = false;
                        walker.startPos = this.el.object3D.position.clone();
                    }
                });
            }
        });
    </script>
</head>

<body>
    <a-scene fog="type: exponential; color: #c9a66b; density: 0.025">
        <!-- Assets EN PREMIER -->
        <a-assets>
            <a-asset-item id="sphynx" src="../assets/modelAvatar/sphynx.glb"></a-asset-item>
            <a-asset-item id="camel" src="../assets/modelAvatar/low_poly_western_camel_camelops_hesternus.glb"></a-asset-item>
            <a-asset-item id="camel_walk" src="../assets/modelAvatar/camel-walk.glb"></a-asset-item>
            <a-asset-item id="anubis" src="../assets/modelAvatar/Anubis Statue.glb"></a-asset-item>
            <a-asset-item id="arch" src="../assets/modelAvatar/Arch.glb"></a-asset-item>
            <a-asset-item id="fence" src="../assets/modelAvatar/Fence Pillar.glb"></a-asset-item>
            <a-asset-item id="coin" src="../assets/modelAvatar/lowpoly_gold_coin.glb"></a-asset-item>
            <a-asset-item id="pyramid" src="../assets/modelAvatar/Pyramid.glb"></a-asset-item>
            <a-asset-item id="sarcophagus" src="../assets/modelAvatar/stone_sarcophagi_cairo_museum.glb"></a-asset-item>
            <a-asset-item id="roman_temple" src="../assets/modelAvatar/low_poly_roman_temple_wip.glb"></a-asset-item>
            <a-asset-item id="chest_glb" src="../assets/modelAvatar/chest.glb"></a-asset-item>
            <a-asset-item id="tent" src="../assets/modelAvatar/Tent.glb"></a-asset-item>
            <a-asset-item id="roman_temple_main" src="../assets/modelAvatar/roman_temple.glb"></a-asset-item>
            <a-asset-item id="stone_pickaxe" src="../assets/modelAvatar/Stone Pickaxe.glb"></a-asset-item>
            <a-asset-item id="mayan_ziggurat" src="../assets/modelAvatar/Mayan Ziggurat.glb"></a-asset-item>
            <a-asset-item id="step_pyramid" src="../assets/modelAvatar/Step Pyramid.glb"></a-asset-item>
            <a-asset-item id="pyramids" src="../assets/modelAvatar/Pyramids.glb"></a-asset-item>
            <a-asset-item id="chest_gold" src="../assets/modelAvatar/Chest Gold.glb"></a-asset-item>
            <a-asset-item id="coffin" src="../assets/modelAvatar/Coffin.glb"></a-asset-item>
            <a-asset-item id="bear_trap" src="../assets/modelAvatar/Bear Trap.glb"></a-asset-item>
            <a-asset-item id="chest_1" src="../assets/modelAvatar/Chest (1).glb"></a-asset-item>
            <a-asset-item id="torture_device" src="../assets/modelAvatar/Torture Device.glb"></a-asset-item>
            <a-asset-item id="spade" src="../assets/modelAvatar/Spade.glb"></a-asset-item>
            <a-asset-item id="trap_door" src="../assets/modelAvatar/Trap Door.glb"></a-asset-item>
            <a-asset-item id="eye_of_horus" src="../assets/modelAvatar/eye_of_horus_educational.glb"></a-asset-item>
            <!-- ASSETS MANQUANTS AJOUTÉS -->
            <a-asset-item id="spider" src="../assets/modelAvatar/animated_low-poly_spider_game-ready.glb"></a-asset-item>
            <a-asset-item id="scorpion" src="../assets/modelAvatar/scorpion.glb"></a-asset-item>
            <a-asset-item id="house" src="../assets/modelAvatar/House.glb"></a-asset-item>
        </a-assets>

        <!-- Sol invisible pour téléportation -->
        <a-plane class="teleportable" rotation="-90 0 0" width="200" height="200" position="0 0.01 0" visible="false" material="opacity: 0"></a-plane>

        <!-- Système de particules pour la tempête de sable - couche principale -->
        <a-entity 
            position="0 5 0"
            particle-system="
                preset: dust;
                color: #d2a679, #c9a66b, #b89968;
                particleCount: 2000;
                maxAge: 8;
                size: 3, 6;
                velocityValue: 4 2 4;
                velocitySpread: 6 2 6;
                accelerationValue: 2 0 0;
                accelerationSpread: 3 0 3;
                opacity: 0.4, 0.1;
                blending: 2;
                texture: https://cdn.aframe.io/examples/particle-system/dust.png;
            ">
        </a-entity>

        <!-- Couche secondaire de tempête (plus haute et plus rapide) -->
        <a-entity 
            position="0 12 0"
            particle-system="
                preset: dust;
                color: #c9a66b, #b89968;
                particleCount: 1500;
                maxAge: 6;
                size: 2, 5;
                velocityValue: 8 1 8;
                velocitySpread: 8 1 8;
                accelerationValue: 3 0 0;
                accelerationSpread: 4 0 4;
                opacity: 0.3, 0.05;
                blending: 2;
                texture: https://cdn.aframe.io/examples/particle-system/dust.png;
            ">
        </a-entity>

        <!-- Tourbillons de sable au sol -->
        <a-entity 
            position="10 0.5 5"
            particle-system="
                preset: dust;
                color: #d2a679;
                particleCount: 800;
                maxAge: 4;
                size: 2, 4;
                velocityValue: 3 3 3;
                velocitySpread: 2 2 2;
                rotationAxis: y;
                rotationAngle: 180;
                opacity: 0.5, 0.1;
                blending: 2;
                texture: https://cdn.aframe.io/examples/particle-system/dust.png;
            ">
        </a-entity>

        <a-entity 
            position="-15 0.5 -10"
            particle-system="
                preset: dust;
                color: #d2a679;
                particleCount: 800;
                maxAge: 4;
                size: 2, 4;
                velocityValue: 3 3 3;
                velocitySpread: 2 2 2;
                rotationAxis: y;
                rotationAngle: -180;
                opacity: 0.5, 0.1;
                blending: 2;
                texture: https://cdn.aframe.io/examples/particle-system/dust.png;
            ">
        </a-entity>

        <!-- Environnement et lumière -->
        <a-entity environment="preset: egypt; groundYScale: 6; fog: 0; skyColor: #c9a66b; horizonColor: #b89968;"></a-entity>
        <a-entity light="type:ambient;intensity:0.7;color:#f4d4a8"></a-entity>
        <a-entity light="type:directional;intensity:0.4;color:#e6c288" position="1 1 0"></a-entity>

        <!-- Sphinx -->
        <a-entity gltf-model="#sphynx" position="-14.976 5.313 21.367" scale="1.5 1.5 1.5" rotation="0 -0.976 0"></a-entity>
        <a-entity gltf-model="#sphynx" position="-27.970 5.313 21.367" scale="1.5 1.5 1.5" rotation="0 -0.976 0"></a-entity>
        <a-entity gltf-model="#sphynx" position="-27.970 5.313 -38.222" scale="1.5 1.5 1.5" rotation="0 180.000 0"></a-entity>
        <a-entity gltf-model="#sphynx" position="-15.029 5.313 -38.222" scale="1.5 1.5 1.5" rotation="0 180.000 0"></a-entity>

        <!-- Chameau animé qui marche -->
        <a-entity gltf-model="#camel_walk" position="0.57776 1.63573 -42.6507" scale="0.05 0.05 0.05" 
            camel-animator camel-walker="distance: 50; speed: 1">
        </a-entity>

        <!-- Chameau statique -->
        <a-entity gltf-model="#camel" position="10 0 0" scale="1 1 1" rotation="0 90 0"></a-entity>

        <!-- Anubis -->
        <a-entity gltf-model="#anubis" position="5 3.034 -2" scale="0.5 0.5 0.5" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#anubis" position="5 3.034 -20" scale="0.5 0.5 0.5" rotation="0 180 0"></a-entity>

        <a-entity gltf-model="#arch" position="4 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#arch" position="2 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#arch" position="0 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#arch" position="-2 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>

        <a-entity gltf-model="#fence" position="-4 0 -13" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#fence" position="-4 0 -7" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#fence" position="-54.532 0 -10.519" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#fence" position="-60.288 0.601 7.779" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#fence" position="-54.532 0.372 7.500" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#fence" position="-61.997 -0.090 -10.519" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#fence" position="-60.240 -0.090 -10.519" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>

        <a-entity gltf-model="#coin" position="2 0 2" scale="0.25 0.25 0.25" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coin" position="-59.820 1.011 -6.418" scale="0.25 0.25 0.25" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coin" position="-59.820 1.131 -6.418" scale="0.25 0.25 0.25" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coin" position="-59.820 1.141 -6.418" scale="0.25 0.25 0.25" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coin" position="-59.820 1.151 -6.418" scale="0.25 0.25 0.25" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coin" position="-59.820 1.161 -6.418" scale="0.25 0.25 0.25" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coin" position="-59.820 1.171 -6.418" scale="0.25 0.25 0.25" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coin" position="-59.820 1.181 -6.418" scale="0.25 0.25 0.25" rotation="0 0 0"></a-entity>

        <a-entity gltf-model="#sarcophagus" position="-56 -15,7  -15" scale="1 1 1" rotation="0 90 0"></a-entity>

        <a-entity id="rig_player" position="0 0 0">
            <a-camera id="camera" position="0 1.6 0" look-controls wasd-controls="enabled: true"></a-camera>

            <a-entity id="rhand" laser-controls="hand: right"></a-entity>

            <a-entity id="lhand" laser-controls="hand: left"
                teleport-controls="cameraRig: #rig_player; teleportOrigin: #camera; button: trigger; curveShootingSpeed: 15; landingMaxAngle: 90; collisionEntities: .teleportable; type: parabolic">
            </a-entity>
        </a-entity>

        <a-entity gltf-model="#tent" position="-17.268 1.986 -9.3" scale="2.5105 2.5105 2.5105"
            rotation="0 180 0"></a-entity>

        <a-entity gltf-model="#stone_pickaxe" position="4.966 -0.022 2.221" scale="0.5 0.5 0.5" rotation="90.000 0 -71.425"></a-entity>
        <a-entity gltf-model="#stone_pickaxe" position="3.090 -0.022 2.221" scale="0.5 0.5 0.5" rotation="90.000 0 100.000"></a-entity>

        <a-entity gltf-model="#roman_temple_main" position="1.000 0 -40.433" scale="0.0085 0.0085 0.0085"
            rotation="0 360 0"></a-entity>

        <a-entity gltf-model="#pyramids" position="-45 0 45" scale="100 100 100" rotation="0 0 0"></a-entity>

        <a-entity gltf-model="#chest_gold" position="-16.659 0.296 -7.821" scale="0.500 0.500 0.500" rotation="0 180.030 0"></a-entity>

        <a-entity gltf-model="#coffin" position="-60 0 0" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coffin" position="-56.711 0 0" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coffin" position="-56.711 0 4.535" scale="1 1 1" rotation="0 0 0"></a-entity>        
        <a-entity gltf-model="#coffin" position="-59.493 0 4.535" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coffin" position="-59.493 0 -5.713" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coffin" position="-57.820 0 -5.713" scale="1 1 1" rotation="0 0 0"></a-entity>

        <a-entity gltf-model="#chest_1" position="-15 0.5 25" scale="0.8 0.8 0.8" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#chest_1" position="-28 0.5 25" scale="0.8 0.8 0.8" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#chest_1" position="-15 0.5 -42" scale="0.8 0.8 0.8" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#chest_1" position="-28 0.5 -42" scale="0.8 0.8 0.8" rotation="0 0 0"></a-entity>

        <a-entity gltf-model="#bear_trap" position="-15 0.5 25" scale="0.8 0.8 0.8" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#bear_trap" position="-28 0.5 25" scale="0.8 0.8 0.8" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#bear_trap" position="-15 0.5 -42" scale="0.8 0.8 0.8" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#bear_trap" position="-28 0.5 -42" scale="0.8 0.8 0.8" rotation="0 0 0"></a-entity>

        <a-entity gltf-model="#torture_device" position="-30.137 0.036 -30" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#torture_device" position="-45.137 0.5 -30" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#torture_device" position="-40.320 0.2 -30" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#torture_device" position="-35.320 0.046 -30" scale="1 1 1" rotation="0 0 0"></a-entity>

        <a-entity gltf-model="#spade" position="-15 0.5 20" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#spade" position="-28 0.5 20" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#spade" position="-15 0.5 -37" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#spade" position="-28 0.5 -37" scale="1 1 1" rotation="0 0 0"></a-entity>

        <a-entity gltf-model="#trap_door" position="2.388 0.033 4.821" scale="1 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#trap_door" position="2.388 0.247 -25.346" scale="1 1 1" rotation="0 90 0"></a-entity>


        <!-- Normal Wall House -->
        <a-entity gltf-model="#normal_wall" position="-14.358 -0.928 -44.021" scale="25.000 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#normal_wall" position="-58.924 -0.928 2.483" scale="25.000 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#normal_wall" position="-21.640 -0.918 27.845" scale="25.000 1 1" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#normal_wall" position="-58.924 -0.928 2.483" scale="25.000 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#normal_wall" position="24.173 -0.928 34.348" scale="8.105 1 1" rotation="0 -89.671 0"></a-entity>
        <a-entity gltf-model="#normal_wall" position="24.173 -0.928 -48.136 " scale="8.105 1 1" rotation="0 -89.671 0"></a-entity>


        <!-- Sol invisible pour téléportation -->
        <a-plane class="teleportable" rotation="-90 0 0" width="200" height="200" position="0 0.01 0" visible="false"
            material="opacity: 0"></a-plane>

        <!-- Environnement et lumière -->
        <a-entity environment="preset: egypt; groundYScale: 6; fog: 0.7"></a-entity>
        <a-entity light="type:ambient;intensity:1.0"></a-entity>

        <!-- Sphinx -->
        <a-entity gltf-model="#sphynx" position="-14.976 5.313 21.367" scale="1.5 1.5 1.5"
            rotation="0 -0.976 0"></a-entity>
        <a-entity gltf-model="#sphynx" position="-27.970 5.313 21.367" scale="1.5 1.5 1.5"
            rotation="0 -0.976 0"></a-entity>
        <a-entity gltf-model="#sphynx" position="-27.970 5.313 -38.222" scale="1.5 1.5 1.5"
            rotation="0 180.000 0"></a-entity>
        <a-entity gltf-model="#sphynx" position="-15.029 5.313 -38.222" scale="1.5 1.5 1.5"
            rotation="0 180.000 0"></a-entity>

        <!-- Animaux et statues -->
        <a-entity gltf-model="../assets/modelAvatar/camel-walk.glb" position="0.57776 1.63573 -42.6507"
            scale="0.05 0.05 0.05" camel-animator camel-walker="distance: 50; speed: 1">
        </a-entity>
        <a-entity gltf-model="#anubis" position="5 3.034 -2" scale="0.5 0.5 0.5" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#anubis" position="5 3.034 -20" scale="0.5 0.5 0.5" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#spider" position="5 0 -5" scale="0.03 0.03 0.03" spider-animator
            spider-walker="speed: 0.8; radius: 15; changeInterval: 2000">
        </a-entity>
        <a-entity gltf-model="#spider" position="-8 0 -3" scale="0.02 0.02 0.02" spider-animator
            spider-walker="speed: 0.5; radius: 12; changeInterval: 3500">
        </a-entity>
        <a-entity gltf-model="#spider" position="3 0 8" scale="0.025 0.025 0.025" spider-animator
            spider-walker="speed: 0.6; radius: 10; changeInterval: 2500">
        </a-entity>
        <a-entity gltf-model="#spider" position="-5 0 10" scale="0.04 0.04 0.04" spider-animator
            spider-walker="speed: 0.4; radius: 8; changeInterval: 4000">
        </a-entity>
        <a-entity gltf-model="#scorpion" position="0 0 5" scale="0.3 0.3 0.3"></a-entity>

        <!-- Arches -->
        <a-entity gltf-model="#arch" position="4 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#arch" position="2 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#arch" position="0 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#arch" position="-2 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>

        <!-- Clôtures -->
        <a-entity gltf-model="#fence" position="-4 0 -13" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#fence" position="-4 0 -7" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#fence" position="-54.532 0 -10.519" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#fence" position="-60.552 0.601 7.779" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#fence" position="-54.532 0.372 7.500" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#fence" position="-61.997 -0.090 -10.519" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>

        <!-- Pièces GRABBABLE -->
        <a-entity gltf-model="#coin" position="2 0.2 2" scale="0.25 0.25 0.25" rotation="0 0 0" grabbable></a-entity>
        <a-entity gltf-model="#coin" position="-59.820 1.011 -6.418" scale="0.25 0.25 0.25" rotation="0 0 0" grabbable></a-entity>
        <a-entity gltf-model="#coin" position="-59.820 1.131 -6.418" scale="0.25 0.25 0.25" rotation="0 0 0" grabbable></a-entity>
        <a-entity gltf-model="#coin" position="-59.820 1.141 -6.418" scale="0.25 0.25 0.25" rotation="0 0 0" grabbable></a-entity>
        <a-entity gltf-model="#coin" position="-59.820 1.151 -6.418" scale="0.25 0.25 0.25" rotation="0 0 0" grabbable></a-entity>
        <a-entity gltf-model="#coin" position="-59.820 1.161 -6.418" scale="0.25 0.25 0.25" rotation="0 0 0" grabbable></a-entity>
        <a-entity gltf-model="#coin" position="-59.820 1.171 -6.418" scale="0.25 0.25 0.25" rotation="0 0 0" grabbable></a-entity>
        <a-entity gltf-model="#coin" position="-59.820 1.181 -6.418" scale="0.25 0.25 0.25" rotation="0 0 0" grabbable></a-entity>

        <!-- Pyramide -->
        <a-entity gltf-model="#pyramid" position="35 -10 -7" scale="8 8 8" rotation="0 133 0"></a-entity>

        <!-- Temples romains -->
        <a-entity gltf-model="#roman_temple" position="-15 2.6 23" scale="7.5 7.5 7.5" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#roman_temple" position="-28 2.6 23" scale="7.5 7.5 7.5" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#roman_temple" position="-15 2.6 -40" scale="7.5 7.5 7.5" rotation="0 360 0"></a-entity>
        <a-entity gltf-model="#roman_temple" position="-28 2.6 -40" scale="7.5 7.5 7.5" rotation="0 360 0"></a-entity>

        <!-- Sarcophage -->
        <a-entity gltf-model="#sarcophagus" position="-56 -15.7 -15" scale="1 1 1" rotation="0 90 0"></a-entity>

        <!-- Coffres -->
        <a-entity gltf-model="#chest_glb" position="38.821 0.943 -4" scale="0.300 0.300 0.300" rotation="0 -90.873 0"></a-entity>
        <a-entity gltf-model="#chest_gold" position="-16.659 0.296 -7.821" scale="0.500 0.500 0.500" rotation="0 180.030 0"></a-entity>
        <a-entity gltf-model="#chest_1" position="-15 0.5 25" scale="0.8 0.8 0.8" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#chest_1" position="-28 0.5 25" scale="0.8 0.8 0.8" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#chest_1" position="-15 0.5 -42" scale="0.8 0.8 0.8" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#chest_1" position="-28 0.5 -42" scale="0.8 0.8 0.8" rotation="0 0 0"></a-entity>

        <!-- Tente -->
        <a-entity gltf-model="#tent" position="-17.268 1.986 -9.3" scale="2.5105 2.5105 2.5105" rotation="0 180 0"></a-entity>

        <!-- Pioches GRABBABLE -->
        <a-entity gltf-model="#stone_pickaxe" position="4.966 0.5 2.221" scale="0.5 0.5 0.5" rotation="90.000 0 -71.425" grabbable></a-entity>
        <a-entity gltf-model="#stone_pickaxe" position="3.090 0.5 2.221" scale="0.5 0.5 0.5" rotation="90.000 0 100.000" grabbable></a-entity>



        <!-- Cercueils -->
        <a-entity gltf-model="#coffin" position="-60 0 0" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coffin" position="-56.711 0 0" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coffin" position="-56.711 0 4.535" scale="1 1 1" rotation="0 0 0"></a-entity>        
        <a-entity gltf-model="#coffin" position="-59.493 0 4.535" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coffin" position="-59.493 0 -5.713" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coffin" position="-57.820 0 -5.713" scale="1 1 1" rotation="0 0 0"></a-entity>

        <!-- Pièges -->
        <a-entity gltf-model="#bear_trap" position="-15 0.5 25" scale="0.8 0.8 0.8" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#bear_trap" position="-28 0.5 25" scale="0.8 0.8 0.8" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#bear_trap" position="-15 0.5 -42" scale="0.8 0.8 0.8" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#bear_trap" position="-28 0.5 -42" scale="0.8 0.8 0.8" rotation="0 0 0"></a-entity>

        <!-- Appareils de torture -->
        <a-entity gltf-model="#torture_device" position="-30.137 0.036 -30" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#torture_device" position="-45.137 0.5 -30" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#torture_device" position="-40.320 0.2 -30" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#torture_device" position="-35.320 0.046 -30" scale="1 1 1" rotation="0 0 0"></a-entity>

        <!-- Pelles -->
        <a-entity gltf-model="#spade" position="-15 0.5 20" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#spade" position="-28 0.5 20" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#spade" position="-15 0.5 -37" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#spade" position="-28 0.5 -37" scale="1 1 1" rotation="0 0 0"></a-entity>

        <!-- Trappes -->
        <a-entity gltf-model="#trap_door" position="2.388 0.033 4.821" scale="1 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#trap_door" position="2.388 0.247 -25.346" scale="1 1 1" rotation="0 90 0"></a-entity>

        <!-- House -->
        <a-entity gltf-model="#house" position="20 0 30" scale="6 6 6" rotation="0 0 0"></a-entity>

        <!-- Rig VR pour Oculus Quest 1 -->
        <a-entity id="rig" position="-18 0 -9">
            <a-camera id="camera" position="0 1.6 0" look-controls wasd-controls="enabled: true"></a-camera>

            <!-- Main droite avec GRAB - Quest Touch Controller -->
            <a-entity id="rhand"
                oculus-touch-controls="hand: right; model: true"
                grab-controls="hand: right; grabDistance: 3">
            </a-entity>

            <!-- Main gauche avec téléportation - Quest Touch Controller -->
            <a-entity id="lhand"
                oculus-touch-controls="hand: left; model: true"
                teleport-controls-custom="cameraRig: #rig; teleportOrigin: #camera; collisionEntities: .teleportable; button: trigger; curveShootingSpeed: 15">
            </a-entity>
        </a-entity>
    </a-scene>
</body>
</html>