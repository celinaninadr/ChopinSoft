<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Environnement désert - Tempête avec Physique</title>
    <meta name="description" content="Environnement desert avec tempête et physique" />

    <script src="https://aframe.io/releases/1.6.0/aframe.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/aframe-environment-component@1.3.7/dist/aframe-environment-component.min.js"></script>

    <style>
        #info {
            position: fixed;
            top: 10px;
            left: 10px;
            background: rgba(0, 0, 0, 0.85);
            color: #0f0;
            padding: 15px;
            font-family: monospace;
            font-size: 13px;
            z-index: 9999;
        }

        #help {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 12px 25px;
            font-family: sans-serif;
            border-radius: 8px;
        }
    </style>

    <script>
        /**
         * COMPOSANT POUR VOIR LES MAINS SUR PC (FPS STYLE)
         */
        AFRAME.registerComponent('simulate-hands-desktop', {
            schema: {
                offset: { type: 'vec3', default: { x: 0.2, y: -0.2, z: -0.5 } },
                color: { type: 'color', default: '#ffccaa' }
            },
            init: function () {
                this.camera = document.querySelector('a-camera');

                // 1. Créer un visuel temporaire (car le modèle VR ne charge pas sur PC)
                this.dummyHand = document.createElement('a-box');
                this.dummyHand.setAttribute('scale', '0.05 0.05 0.15');
                this.dummyHand.setAttribute('color', this.data.color);
                this.dummyHand.setAttribute('opacity', '0.8');
                this.el.appendChild(this.dummyHand);

                // 2. Gestion VR : Cacher le cube moche quand on entre en VR
                this.el.sceneEl.addEventListener('enter-vr', () => {
                    this.dummyHand.setAttribute('visible', false);
                });
                this.el.sceneEl.addEventListener('exit-vr', () => {
                    this.dummyHand.setAttribute('visible', true);
                });
            },
            tick: function () {
                // Si on est en VR, on laisse le hand-controls gérer la position
                if (this.el.sceneEl.is('vr-mode') || !this.camera) return;

                // SINON (Desktop): On colle la main à la caméra avec un décalage
                const camObj = this.camera.object3D;
                const myObj = this.el.object3D;

                myObj.position.copy(camObj.position);
                myObj.rotation.copy(camObj.rotation);

                // Appliquer le décalage (offset) pour mettre la main devant les yeux
                myObj.translateX(this.data.offset.x);
                myObj.translateY(this.data.offset.y);
                myObj.translateZ(this.data.offset.z);
            }
        });
        /**
         * SYSTÈME DE PHYSIQUE MAISON
         */
        AFRAME.registerComponent('simple-physics', {
            schema: {
                mass: { type: 'number', default: 1 },
                restitution: { type: 'number', default: 0.5 }, // rebond
                friction: { type: 'number', default: 0.98 },
                floorY: { type: 'number', default: 0 }
            },

            init: function () {
                this.velocity = new THREE.Vector3(0, 0, 0);
                this.gravity = -15;
                this.isGrabbed = false;

                // Sauvegarder la couleur originale
                const material = this.el.getAttribute('material');
                this.originalColor = material ? material.color : '#ff0000';

                // Calculer la taille de l'objet
                this.halfHeight = 0.2;
                const geometry = this.el.getAttribute('geometry');
                if (geometry) {
                    if (geometry.height) this.halfHeight = geometry.height / 2;
                    else if (geometry.radius) this.halfHeight = geometry.radius;
                }

                // Pour les modèles GLB, essayer d'obtenir la taille
                this.el.addEventListener('model-loaded', () => {
                    const mesh = this.el.getObject3D('mesh');
                    if (mesh) {
                        const box = new THREE.Box3().setFromObject(mesh);
                        const size = box.getSize(new THREE.Vector3());
                        this.halfHeight = size.y / 2;
                    }
                });
            },

            grab: function () {
                this.isGrabbed = true;
                this.velocity.set(0, 0, 0);

                // Changer couleur si c'est une primitive
                if (this.el.getAttribute('geometry')) {
                    this.el.setAttribute('material', 'color', '#00ff00');
                }

                document.getElementById('status').textContent = '✅ ATTRAPÉ!';
            },

            release: function (throwVelocity) {
                this.isGrabbed = false;
                if (throwVelocity) {
                    this.velocity.copy(throwVelocity).multiplyScalar(15);
                }

                // Restaurer couleur
                if (this.el.getAttribute('geometry')) {
                    this.el.setAttribute('material', 'color', this.originalColor);
                }

                document.getElementById('status').textContent = '🚀 Lancé!';
            },

            tick: function (time, delta) {
                if (this.isGrabbed) return;

                const dt = Math.min(delta / 1000, 0.05);
                const pos = this.el.object3D.position;

                // Gravité
                this.velocity.y += this.gravity * dt;

                // Appliquer vélocité
                pos.x += this.velocity.x * dt;
                pos.y += this.velocity.y * dt;
                pos.z += this.velocity.z * dt;

                // Collision avec le sol
                const groundY = this.data.floorY + this.halfHeight;
                if (pos.y < groundY) {
                    pos.y = groundY;
                    this.velocity.y *= -this.data.restitution;
                    this.velocity.x *= this.data.friction;
                    this.velocity.z *= this.data.friction;

                    if (Math.abs(this.velocity.y) < 0.5) {
                        this.velocity.y = 0;
                    }
                }

                // Friction air
                this.velocity.x *= 0.995;
                this.velocity.z *= 0.995;
            }
        });

        /**
         * SYSTÈME DE GRAB DESKTOP
         */
        AFRAME.registerComponent('grab-system', {
            init: function () {
                this.grabbed = null;
                this.hovered = null;
                this.grabDistance = 3;
                this.ray = new THREE.Raycaster();
                this.mouse = new THREE.Vector2();
                this.lastPos = new THREE.Vector3();
                this.velocity = new THREE.Vector3();
                this.smoothVel = new THREE.Vector3();

                // Curseur
                this.dot = document.createElement('a-sphere');
                this.dot.setAttribute('radius', '0.05');
                this.dot.setAttribute('color', '#00aaff');
                this.dot.setAttribute('material', 'shader:flat');
                this.el.sceneEl.appendChild(this.dot);

                window.addEventListener('mousemove', e => {
                    this.mouse.x = (e.clientX / window.innerWidth) * 2 - 1;
                    this.mouse.y = -(e.clientY / window.innerHeight) * 2 + 1;
                });

                window.addEventListener('mousedown', () => {
                    if (this.grabbed) {
                        const phys = this.grabbed.components['simple-physics'];
                        if (phys) phys.release(this.smoothVel);
                        this.grabbed = null;
                        this.dot.setAttribute('visible', true);
                    } else if (this.hovered) {
                        const phys = this.hovered.components['simple-physics'];
                        if (phys) {
                            phys.grab();
                            this.grabbed = this.hovered;

                            const objPos = new THREE.Vector3();
                            this.grabbed.object3D.getWorldPosition(objPos);
                            const camPos = new THREE.Vector3();
                            this.el.sceneEl.camera.getWorldPosition(camPos);
                            this.grabDistance = objPos.distanceTo(camPos);
                            this.lastPos.copy(objPos);
                            this.smoothVel.set(0, 0, 0);

                            this.dot.setAttribute('visible', false);
                        }
                    }
                });

                window.addEventListener('wheel', e => {
                    if (this.grabbed) {
                        this.grabDistance += e.deltaY * -0.005;
                        this.grabDistance = Math.max(1, Math.min(10, this.grabDistance));
                    }
                });
            },

            tick: function () {
                const cam = this.el.sceneEl.camera;
                if (!cam) return;

                this.ray.setFromCamera(this.mouse, cam);
                const dir = this.ray.ray.direction.clone().normalize();
                const origin = this.ray.ray.origin.clone();

                if (this.grabbed) {
                    const targetPos = origin.clone().add(dir.clone().multiplyScalar(this.grabDistance));
                    const currentPos = this.grabbed.object3D.position.clone();
                    this.velocity.subVectors(targetPos, currentPos);
                    this.smoothVel.lerp(this.velocity, 0.3);
                    this.lastPos.copy(currentPos);
                    this.grabbed.object3D.position.lerp(targetPos, 0.3);
                    return;
                }

                // Recherche d'objets avec simple-physics
                const objs = document.querySelectorAll('[simple-physics]');
                let best = null, bestD = 999, pt = null;

                objs.forEach(o => {
                    const m = o.getObject3D('mesh');
                    if (!m) return;
                    const h = this.ray.intersectObject(m, true);
                    if (h.length && h[0].distance < bestD) {
                        bestD = h[0].distance;
                        best = o;
                        pt = h[0].point;
                    }
                });

                if (best && bestD < 20) {
                    this.hovered = best;
                    this.dot.setAttribute('color', '#00ff00');
                    this.dot.object3D.position.copy(pt);
                    document.getElementById('status').textContent = '👆 CLIC pour attraper';
                } else {
                    this.hovered = null;
                    this.dot.setAttribute('color', '#00aaff');
                    const dotPos = origin.clone().add(dir.multiplyScalar(5));
                    this.dot.object3D.position.copy(dotPos);
                    document.getElementById('status').textContent = '🔵 Vise un objet...';
                }
            }
        });

        /**
         * 1. TELEPORT CONTROLS CUSTOM
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
                curveMissColor: { type: 'color', default: '#ff9900' },
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
                this.el.addEventListener('triggerdown', this.onButtonDown.bind(this));
                this.el.addEventListener('triggerup', this.onButtonUp.bind(this));
                this.el.addEventListener('selectstart', this.onButtonDown.bind(this));
                this.el.addEventListener('selectend', this.onButtonUp.bind(this));
                console.log('✅ Teleport controls initialized');
            },
            createCurveLine: function () {
                const geometry = new THREE.BufferGeometry();
                const positions = new Float32Array(this.data.curveNumberPoints * 3);
                geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
                const material = new THREE.LineBasicMaterial({ color: this.data.curveMissColor, linewidth: 3 });
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
                console.log('🎯 Trigger pressed');
                this.active = true;
                this.curveLine.visible = true;
            },
            onButtonUp: function () {
                console.log('🎯 Trigger released');
                if (this.active && this.hit && this.hitPoint) {
                    console.log('✨ Teleporting to:', this.hitPoint);
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
            tick: function () { if (!this.active) return; this.updateCurve(); },
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
                if (this.curveLine) { this.el.sceneEl.object3D.remove(this.curveLine); }
                if (this.hitMarker) { this.hitMarker.parentNode.removeChild(this.hitMarker); }
            }
        });

        /**
         * 2. ANIMATION & IA COMPONENTS (Camel & Spider)
         */
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
            tick: function () { if (this.mixer) this.mixer.update(this.clock.getDelta()); }
        });

        AFRAME.registerComponent('camel-walker', {
            schema: { distance: { type: 'number', default: 15 }, speed: { type: 'number', default: 2 } },
            init: function () { this.direction = 1; this.startZ = this.el.object3D.position.z; },
            tick: function (time, deltaTime) {
                const position = this.el.object3D.position;
                const rotation = this.el.object3D.rotation;
                const moveSpeed = this.data.speed * (deltaTime / 1000) * this.direction;
                position.z += moveSpeed;
                if (this.direction === 1 && position.z >= this.startZ + this.data.distance) {
                    this.direction = -1; rotation.y = 0;
                } else if (this.direction === -1 && position.z <= this.startZ - this.data.distance) {
                    this.direction = 1; rotation.y = Math.PI;
                }
            }
        });

        AFRAME.registerComponent('spider-animator', {
            init: function () {
                this.mixer = null;
                this.clock = new THREE.Clock();
                this.el.addEventListener('model-loaded', () => {
                    const model = this.el.getObject3D('mesh');
                    if (model && model.animations && model.animations.length > 0) {
                        this.mixer = new THREE.AnimationMixer(model);
                        let animIndex = model.animations.length > 1 ? 1 : 0;
                        const action = this.mixer.clipAction(model.animations[animIndex]);
                        action.play();
                    }
                });
            },
            tick: function () { if (this.mixer) this.mixer.update(this.clock.getDelta()); }
        });

        AFRAME.registerComponent('spider-walker', {
            schema: { speed: { type: 'number', default: 0.5 }, radius: { type: 'number', default: 10 }, changeInterval: { type: 'number', default: 3000 } },
            init: function () {
                this.startPos = this.el.object3D.position.clone();
                this.targetAngle = Math.random() * Math.PI * 2;
                this.lastChange = 0;
                this.el.object3D.rotation.y = this.targetAngle - Math.PI;
                this.paused = false;
            },
            tick: function (time, deltaTime) {
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
                const distFromStart = Math.sqrt(Math.pow(position.x - this.startPos.x, 2) + Math.pow(position.z - this.startPos.z, 2));
                if (distFromStart > this.data.radius) {
                    const angleToCenter = Math.atan2(this.startPos.x - position.x, this.startPos.z - position.z);
                    this.targetAngle = angleToCenter + Math.PI;
                }
            }
        });

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

        /**
         * 3. GRABBABLE SYSTEM VR (ancien système pour les contrôleurs)
         */
        AFRAME.registerComponent('grabbable', {
            schema: { enabled: { type: 'boolean', default: true } },
            init: function () {
                this.isGrabbed = false;
                this.grabber = null;
                this.originalPosition = new THREE.Vector3();
                this.originalRotation = new THREE.Euler();
                this.el.addEventListener('raycaster-intersected', () => { this.el.emit('hovered'); });
                this.el.addEventListener('raycaster-intersected-cleared', () => { this.el.emit('unhovered'); });
            },
            grab: function (hand) {
                if (!this.data.enabled || this.isGrabbed) return;
                this.isGrabbed = true;
                this.grabber = hand;
                this.el.object3D.getWorldPosition(this.originalPosition);
                this.originalRotation.copy(this.el.object3D.rotation);
                hand.object3D.attach(this.el.object3D);
                this.el.emit('grabbed', { hand: hand });
                console.log('✊ Objet attrapé');
            },
            release: function () {
                if (!this.isGrabbed) return;
                this.el.sceneEl.object3D.attach(this.el.object3D);
                this.isGrabbed = false;
                this.grabber = null;
                this.el.emit('released');
                console.log('✋ Objet relâché');
            }
        });

        AFRAME.registerComponent('grab-controls', {
            schema: { hand: { type: 'string', default: 'right' }, grabDistance: { type: 'number', default: 5 } },
            init: function () {
                this.grabbedObject = null;
                this.hoveredObject = null;
                this.isGrabbing = false;
                this.raycaster = new THREE.Raycaster();
                this.raycaster.far = this.data.grabDistance;
                this.createLaser();
                const grabEvents = ['gripdown', 'squeezestart'];
                const releaseEvents = ['gripup', 'squeezeend'];
                grabEvents.forEach(evt => {
                    this.el.addEventListener(evt, () => {
                        console.log('🤜 Grip pressed');
                        this.tryGrab();
                    });
                });
                releaseEvents.forEach(evt => {
                    this.el.addEventListener(evt, () => {
                        console.log('🤚 Grip released');
                        this.release();
                    });
                });
                console.log('✅ Grab controls initialized for', this.data.hand, 'hand');
            },
            createLaser: function () {
                this.laser = document.createElement('a-entity');
                this.laser.setAttribute('geometry', { primitive: 'cylinder', radius: 0.005, height: this.data.grabDistance, segmentsRadial: 8 });
                this.laser.setAttribute('material', { color: '#00aaff', opacity: 0.8, shader: 'flat' });
                this.laser.setAttribute('position', '0 0 -' + (this.data.grabDistance / 2));
                this.laser.setAttribute('rotation', '90 0 0');
                this.el.appendChild(this.laser);
                this.hitSphere = document.createElement('a-sphere');
                this.hitSphere.setAttribute('radius', '0.05');
                this.hitSphere.setAttribute('color', '#00aaff');
                this.hitSphere.setAttribute('material', 'shader: flat');
                this.hitSphere.setAttribute('position', '0 0 -' + this.data.grabDistance);
                this.el.appendChild(this.hitSphere);
            },
            tryGrab: function () {
                if (this.isGrabbing) return;
                if (this.hoveredObject) {
                    const grabbable = this.hoveredObject.components.grabbable;
                    if (grabbable) {
                        grabbable.grab(this.el);
                        this.grabbedObject = this.hoveredObject;
                        this.isGrabbing = true;
                        this.laser.setAttribute('visible', false);
                        this.hitSphere.setAttribute('visible', false);
                    }
                }
            },
            release: function () {
                if (!this.isGrabbing) return;
                if (this.grabbedObject) {
                    const grabbable = this.grabbedObject.components.grabbable;
                    if (grabbable) grabbable.release();
                }
                this.grabbedObject = null;
                this.isGrabbing = false;
                this.laser.setAttribute('visible', true);
                this.hitSphere.setAttribute('visible', true);
            },
            tick: function () {
                if (this.isGrabbing) return;
                const pos = new THREE.Vector3();
                const dir = new THREE.Vector3(0, 0, -1);
                this.el.object3D.getWorldPosition(pos);
                dir.applyQuaternion(this.el.object3D.quaternion);
                this.raycaster.set(pos, dir.normalize());
                const grabbables = document.querySelectorAll('[grabbable]');
                let closest = null;
                let closestDist = Infinity;
                grabbables.forEach(entity => {
                    const mesh = entity.getObject3D('mesh');
                    if (!mesh) return;
                    const hits = this.raycaster.intersectObject(mesh, true);
                    if (hits.length > 0 && hits[0].distance < closestDist) {
                        closestDist = hits[0].distance;
                        closest = entity;
                    }
                });
                if (closest && closestDist <= this.data.grabDistance) {
                    this.hoveredObject = closest;
                    this.laser.setAttribute('material', 'color', '#00ff00');
                    this.hitSphere.setAttribute('color', '#00ff00');
                    this.laser.setAttribute('geometry', 'height', closestDist);
                    this.laser.setAttribute('position', '0 0 -' + (closestDist / 2));
                    this.hitSphere.setAttribute('position', '0 0 -' + closestDist);
                } else {
                    this.hoveredObject = null;
                    this.laser.setAttribute('material', 'color', '#00aaff');
                    this.hitSphere.setAttribute('color', '#00aaff');
                    this.laser.setAttribute('geometry', 'height', this.data.grabDistance);
                    this.laser.setAttribute('position', '0 0 -' + (this.data.grabDistance / 2));
                    this.hitSphere.setAttribute('position', '0 0 -' + this.data.grabDistance);
                }
            }
        });
    </script>
</head>

<body>
    <div id="info">
        <b>🎮 Désert VR - Physique</b><br>
        <span id="status">Prêt!</span>
    </div>
    <div id="help">
        <b>Desktop:</b> CLIC = attraper/lancer | Molette = distance | <b>VR:</b> GRIP = attraper | TRIGGER = téléport
    </div>

    <a-scene fog="type: exponential; color: #dcb271; density: 0.04">

        <a-assets>
            <a-asset-item id="sphynx" src="../assets/modelAvatar/sphynx.glb"></a-asset-item>
            <a-asset-item id="stand" src="../assets/modelAvatar/Market Stand.glb"></a-asset-item>
            <a-asset-item id="camel"
                src="../assets/modelAvatar/low_poly_western_camel_camelops_hesternus.glb"></a-asset-item>
            <a-asset-item id="camel_walk" src="../assets/modelAvatar/camel-walk.glb"></a-asset-item>
            <a-asset-item id="anubis" src="../assets/modelAvatar/Anubis Statue.glb"></a-asset-item>
            <a-asset-item id="arch" src="../assets/modelAvatar/Arch.glb"></a-asset-item>
            <a-asset-item id="fence" src="../assets/modelAvatar/Fence Pillar.glb"></a-asset-item>
            <a-asset-item id="coin" src="../assets/modelAvatar/lowpoly_gold_coin.glb"></a-asset-item>
            <a-asset-item id="pyramid" src="../assets/modelAvatar/Pyramid.glb"></a-asset-item>
            <a-asset-item id="sarcophagus" src="../assets/modelAvatar/stone_sarcophagi_cairo_museum.glb"></a-asset-item>
            <a-asset-item id="roman_temple" src="../assets/modelAvatar/low_poly_roman_temple_wip.glb"></a-asset-item>
            <a-asset-item id="roman_temple_main" src="../assets/modelAvatar/roman_temple.glb"></a-asset-item>
            <a-asset-item id="chest_glb" src="../assets/modelAvatar/chest.glb"></a-asset-item>
            <a-asset-item id="tent" src="../assets/modelAvatar/Tent.glb"></a-asset-item>
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
            <a-asset-item id="door" src="../assets/modelAvatar/egyptian_door.glb"></a-asset-item>
            <a-asset-item id="normal_wall" src="../assets/modelAvatar/Normal Wall.glb"></a-asset-item>

            <a-asset-item id="market5" src="../assets/modelAvatar/Market Stalls5.glb"></a-asset-item>
            <a-asset-item id="market4" src="../assets/modelAvatar/Market Stalls4.glb"></a-asset-item>
            <a-asset-item id="market2" src="../assets/modelAvatar/Market Stalls Compact.glb"></a-asset-item>
            <a-asset-item id="market" src="../assets/modelAvatar/Market Stalls.glb"></a-asset-item>

            <a-asset-item id="spider"
                src="../assets/modelAvatar/animated_low-poly_spider_game-ready.glb"></a-asset-item>
            <a-asset-item id="scorpion" src="../assets/modelAvatar/scorpion.glb"></a-asset-item>
        </a-assets>

        <a-plane class="teleportable" rotation="-90 0 0" width="200" height="200" position="0 0.01 0" visible="false"
            material="opacity: 0"></a-plane>

        <a-entity
            environment="preset: egypt; groundYScale: 6; fog: 0; skyColor: #dcb271; horizonColor: #dcb271; lighting: none"></a-entity>

        <a-entity light="type: hemisphere; color: #fff5e6; groundColor: #dcb271; intensity: 0.6"></a-entity>
        <a-entity light="type: directional; intensity: 1.2; color: #fff0dd" position="-0.5 1 0.5"></a-entity>

        <a-entity gltf-model="#pyramids" position="-45 0 45" scale="100 100 100" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#pyramid" position="35 -10 -7" scale="8 8 8" rotation="0 133 0"></a-entity>
        <a-entity gltf-model="#roman_temple_main" position="1.000 0 -40.433" scale="0.0085 0.0085 0.0085"
            rotation="0 360 0"></a-entity>

        <a-entity gltf-model="#door" position="6.849 2.943 -10.000" scale="4 4 4"
            rotation="-30.374 -93.378 1.064"></a-entity>
        <a-entity gltf-model="#arch" position="4 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#arch" position="2 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#arch" position="0 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#arch" position="-2 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>

        <a-entity gltf-model="#normal_wall" position="-14.358 -0.928 -44.021" scale="25.000 1 1"
            rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#normal_wall" position="-58.924 -0.928 2.483" scale="25.000 1 1"
            rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#normal_wall" position="-21.640 -0.918 27.845" scale="25.000 1 1"
            rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#normal_wall" position="24.173 -0.928 34.348" scale="8.105 1 1"
            rotation="0 -89.671 0"></a-entity>
        <a-entity gltf-model="#normal_wall" position="24.173 -0.928 -48.136 " scale="8.105 1 1"
            rotation="0 -89.671 0"></a-entity>

        <a-entity gltf-model="#roman_temple" position="-15 2.6 23" scale="7.5 7.5 7.5" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#roman_temple" position="-28 2.6 23" scale="7.5 7.5 7.5" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#roman_temple" position="-15 2.6 -40" scale="7.5 7.5 7.5" rotation="0 360 0"></a-entity>
        <a-entity gltf-model="#roman_temple" position="-28 2.6 -40" scale="7.5 7.5 7.5" rotation="0 360 0"></a-entity>

        <a-entity gltf-model="#tent" position="-17.268 1.986 -9.3" scale="2.5105 2.5105 2.5105"
            rotation="0 180 0"></a-entity>

        <a-entity gltf-model="#sphynx" position="-14.976 5.313 21.367" scale="1.5 1.5 1.5"
            rotation="0 -0.976 0"></a-entity>
        <a-entity gltf-model="#sphynx" position="-27.970 5.313 21.367" scale="1.5 1.5 1.5"
            rotation="0 -0.976 0"></a-entity>
        <a-entity gltf-model="#sphynx" position="-27.970 5.313 -38.222" scale="1.5 1.5 1.5"
            rotation="0 180.000 0"></a-entity>
        <a-entity gltf-model="#sphynx" position="-15.029 5.313 -38.222" scale="1.5 1.5 1.5"
            rotation="0 180.000 0"></a-entity>

        <a-entity gltf-model="#anubis" position="5 3.034 -2" scale="0.5 0.5 0.5" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#anubis" position="5 3.034 -20" scale="0.5 0.5 0.5" rotation="0 180 0"></a-entity>

        <a-entity gltf-model="#fence" position="-4 0 -13" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#fence" position="-4 0 -7" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#fence" position="-54.532 0 -10.519" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#fence" position="-60.288 0.601 7.779" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#fence" position="-54.532 0.372 7.500" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#fence" position="-61.997 -0.090 -10.519" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#fence" position="-60.240 -0.090 -10.519" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>

        <a-entity gltf-model="#stand" position="-20.907 0.078 21.367" scale="1.5 1.5 1.5"
            rotation="0 -90.629 0"></a-entity>
        <a-entity gltf-model="#market5" position="-52.200 0.247 -41.422" scale="4 4 4" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#market" position="2.388 0.247 -25.346" scale="4 4 4" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#market" position="-52.520 0.247 20.315" scale="4 4 4" rotation="0 22.197 0"></a-entity>
        <a-entity gltf-model="#market2" position="-38.148 0.247 22.461" scale="4 4 4" rotation="0 -6.143 0"></a-entity>
        <a-entity gltf-model="#market4" position="-38.148 0.247 22.461" scale="4 4 4" rotation="0 -6.143 0"></a-entity>

        <a-entity gltf-model="#sarcophagus" position="31.956 -6.141 -15.000" scale="1 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#coffin" position="-60 0 0" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coffin" position="-56.711 0 0" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coffin" position="-56.711 0 4.535" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coffin" position="-59.493 0 4.535" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coffin" position="-59.493 0 -5.713" scale="1 1 1" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coffin" position="-57.820 0 -5.713" scale="1 1 1" rotation="0 0 0"></a-entity>

        <a-entity gltf-model="#chest_gold" position="-16.659 0.296 -7.821" scale="0.5 0.5 0.5"
            rotation="0 180.030 0"></a-entity>
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

        <!-- OBJETS AVEC PHYSIQUE (nouveau système) -->
        <a-entity gltf-model="#stone_pickaxe" position="4.966 2 2.221" scale="0.5 0.5 0.5" rotation="90.000 0 -71.425"
            simple-physics="mass: 0.8; restitution: 0.3"></a-entity>
        <a-entity gltf-model="#stone_pickaxe" position="3.090 2 2.221" scale="0.5 0.5 0.5" rotation="90.000 0 100.000"
            simple-physics="mass: 0.8; restitution: 0.3"></a-entity>

        <!-- Pièces avec physique -->
        <a-entity gltf-model="#coin" position="2 2 2" scale="0.25 0.25 0.25" rotation="0 0 0"
            simple-physics="mass: 0.2; restitution: 0.7"></a-entity>
        <a-entity gltf-model="#coin" position="-59.820 2 -6.418" scale="0.25 0.25 0.25" rotation="0 0 0"
            simple-physics="mass: 0.2; restitution: 0.7"></a-entity>
        <a-entity gltf-model="#coin" position="-58.820 2.5 -6.418" scale="0.25 0.25 0.25" rotation="0 0 0"
            simple-physics="mass: 0.2; restitution: 0.7"></a-entity>
        <a-entity gltf-model="#coin" position="-57.820 3 -6.418" scale="0.25 0.25 0.25" rotation="0 0 0"
            simple-physics="mass: 0.2; restitution: 0.7"></a-entity>

        <!-- Objets de test avec physique -->
        <a-box position="-10 3 -5" color="#ff0000" geometry="width: 0.4; height: 0.4; depth: 0.4"
            simple-physics="mass: 1; restitution: 0.6"></a-box>
        <a-sphere position="-12 3 -5" color="#ff6600" geometry="radius: 0.3"
            simple-physics="mass: 0.5; restitution: 0.8"></a-sphere>
        <a-box position="-14 3 -5" color="#ffff00" geometry="width: 0.3; height: 0.3; depth: 0.3"
            simple-physics="mass: 0.3; restitution: 0.7"></a-box>

        <a-entity gltf-model="#camel_walk" position="0.57776 1.63573 -42.6507" scale="0.05 0.05 0.05" camel-animator
            camel-walker="distance: 50; speed: 1"></a-entity>

        <!-- Araignées avec ancien système grabbable pour VR -->
        <a-entity gltf-model="#spider" position="5 0 -5" scale="0.03 0.03 0.03" spider-animator grabbable
            stoppable-on-grab spider-walker="speed: 0.8; radius: 15; changeInterval: 2000"></a-entity>
        <a-entity gltf-model="#spider" position="-8 0 -3" scale="0.02 0.02 0.02" spider-animator grabbable
            stoppable-on-grab spider-walker="speed: 0.5; radius: 12; changeInterval: 3500"></a-entity>
        <a-entity gltf-model="#spider" position="3 0 8" scale="0.025 0.025 0.025" spider-animator grabbable
            stoppable-on-grab spider-walker="speed: 0.6; radius: 10; changeInterval: 2500"></a-entity>

        <!-- Scorpion avec physique -->
        <a-entity gltf-model="#scorpion" position="0 2 5" scale="0.3 0.3 0.3"
            simple-physics="mass: 1.2; restitution: 0.4"></a-entity>

        <!-- RIG VR AVEC MAINS 3D -->
        <a-entity id="rig" position="-18 0 -9">
            <a-camera id="camera" position="0 1.6 0" look-controls wasd-controls="enabled: true" grab-system></a-camera>

            <a-entity id="rhand" hand-controls="hand: right; handModelStyle: lowPoly; color: #ffccaa"
                grab-controls="hand: right; grabDistance: 5"
                simulate-hands-desktop="offset: 0.2 -0.2 -0.4; color: #ffccaa">
            </a-entity>

            <a-entity id="lhand" hand-controls="hand: left; handModelStyle: lowPoly; color: #ffccaa"
                teleport-controls-custom="cameraRig: #rig; teleportOrigin: #camera; collisionEntities: .teleportable;"
                simulate-hands-desktop="offset: -0.2 -0.2 -0.4; color: #ffccaa">
            </a-entity>
        </a-entity>
    </a-scene>
</body>

</html>