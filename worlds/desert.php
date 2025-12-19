<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Environnement désert - Tempête</title>
    <meta name="description" content="Environnement desert avec tempête" />
    <script src="https://aframe.io/releases/1.6.0/aframe.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/aframe-environment-component@1.3.7/dist/aframe-environment-component.min.js"></script>

    <script src="https://unpkg.com/aframe-teleport-controls@0.3.1/dist/aframe-teleport-controls.min.js"></script>

    <script>

        /* ============================================================
         * ANIMATION DU CHAMEAU
         * Joue l'animation de marche du modèle GLB
         * ============================================================ */
        AFRAME.registerComponent('camel-animator', {
            init: function () {
                this.mixer = null;
                this.clock = new THREE.Clock();

                // Attendre que le modèle soit chargé
                this.el.addEventListener('model-loaded', () => {
                    const model = this.el.getObject3D('mesh');
                    if (model && model.animations && model.animations.length > 0) {
                        // Créer le mixer d'animation
                        this.mixer = new THREE.AnimationMixer(model);

                        // Chercher l'animation de marche ou prendre la première
                        let animToPlay = model.animations.find(a => a.name.includes('Walk'));
                        if (!animToPlay) animToPlay = model.animations[0];

                        // Jouer l'animation en boucle
                        const action = this.mixer.clipAction(animToPlay);
                        action.play();
                    }
                });
            },

            tick: function () {
                // Mettre à jour l'animation
                if (this.mixer) {
                    this.mixer.update(this.clock.getDelta());
                }
            }
        });


        /* ============================================================
         * DÉPLACEMENT DU CHAMEAU
         * Fait marcher le chameau en ligne droite avec demi-tour
         * ============================================================ */
        AFRAME.registerComponent('camel-walker', {
            schema: {
                distance: { type: 'number', default: 15 }, // Distance de marche
                speed: { type: 'number', default: 2 }      // Vitesse
            },

            init: function () {
                this.direction = 1;                              // 1 = avance, -1 = recule
                this.startZ = this.el.object3D.position.z;       // Position de départ
            },

            tick: function (time, deltaTime) {
                const position = this.el.object3D.position;
                const rotation = this.el.object3D.rotation;

                // Déplacer le chameau
                const moveSpeed = this.data.speed * (deltaTime / 1000) * this.direction;
                position.z += moveSpeed;

                // Demi-tour aux extrémités
                if (this.direction === 1 && position.z >= this.startZ + this.data.distance) {
                    this.direction = -1;
                    rotation.y = 0;
                } else if (this.direction === -1 && position.z <= this.startZ - this.data.distance) {
                    this.direction = 1;
                    rotation.y = Math.PI;
                }
            }
        });


        /* ============================================================
         * ANIMATION DE L'ARAIGNÉE
         * Joue l'animation du modèle GLB
         * ============================================================ */
        AFRAME.registerComponent('spider-animator', {
            init: function () {
                this.mixer = null;
                this.clock = new THREE.Clock();

                this.el.addEventListener('model-loaded', () => {
                    const model = this.el.getObject3D('mesh');
                    if (model && model.animations && model.animations.length > 0) {
                        this.mixer = new THREE.AnimationMixer(model);
                        // Prendre l'animation index 1 si disponible (souvent la marche)
                        const animIndex = model.animations.length > 1 ? 1 : 0;
                        const action = this.mixer.clipAction(model.animations[animIndex]);
                        action.play();
                    }
                });
            },

            tick: function () {
                if (this.mixer) {
                    this.mixer.update(this.clock.getDelta());
                }
            }
        });


        /* ============================================================
         * DÉPLACEMENT DE L'ARAIGNÉE
         * Mouvement aléatoire dans un rayon défini
         * ============================================================ */
        AFRAME.registerComponent('spider-walker', {
            schema: {
                speed: { type: 'number', default: 0.5 },          // Vitesse
                radius: { type: 'number', default: 10 },          // Rayon de déplacement
                changeInterval: { type: 'number', default: 3000 } // Temps entre changements de direction (ms)
            },

            init: function () {
                this.startPos = this.el.object3D.position.clone(); // Position de départ
                this.targetAngle = Math.random() * Math.PI * 2;    // Direction cible
                this.lastChange = 0;                               // Dernier changement
                this.paused = false;                               // Pause quand attrapé

                // Orientation initiale
                this.el.object3D.rotation.y = this.targetAngle - Math.PI;
            },

            tick: function (time, deltaTime) {
                // Ne pas bouger si attrapé
                if (this.paused) return;

                const position = this.el.object3D.position;
                const rotation = this.el.object3D.rotation;

                // Changer de direction périodiquement
                if (time - this.lastChange > this.data.changeInterval) {
                    this.targetAngle = Math.random() * Math.PI * 2;
                    this.lastChange = time;
                }

                // Rotation douce vers la cible
                let angleDiff = this.targetAngle - rotation.y;
                while (angleDiff > Math.PI) angleDiff -= Math.PI * 2;
                while (angleDiff < -Math.PI) angleDiff += Math.PI * 2;
                rotation.y += angleDiff * 0.05;

                // Avancer
                const moveSpeed = this.data.speed * (deltaTime / 1000);
                position.x += Math.sin(rotation.y + Math.PI) * moveSpeed;
                position.z += Math.cos(rotation.y + Math.PI) * moveSpeed;

                // Vérifier si on sort du rayon autorisé
                const distFromStart = Math.sqrt(
                    Math.pow(position.x - this.startPos.x, 2) +
                    Math.pow(position.z - this.startPos.z, 2)
                );

                // Si trop loin, faire demi-tour vers le centre
                if (distFromStart > this.data.radius) {
                    const angleToCenter = Math.atan2(
                        this.startPos.x - position.x,
                        this.startPos.z - position.z
                    );
                    this.targetAngle = angleToCenter + Math.PI;
                }
            }
        });


        /* ============================================================
         * COMPOSANT GRABBABLE AVEC PHYSIQUE MAISON
         * Permet d'attraper et lancer des objets
         * 
         * Fonctionnalités:
         * - Gravité réaliste
         * - Rebonds au sol
         * - Lancer avec vélocité
         * ============================================================ */
        AFRAME.registerComponent('grabbable', {
            schema: {
                enabled: { type: 'boolean', default: true },      // Peut être attrapé ?
                mass: { type: 'number', default: 1 },             // Masse (affecte l'inertie)
                restitution: { type: 'number', default: 0.5 },    // Rebond (0-1)
                floorY: { type: 'number', default: 0 },           // Hauteur du sol
                gravity: { type: 'number', default: -15 }         // Force de gravité
            },

            init: function () {
                // === ÉTAT ===
                this.isGrabbed = false;           // Actuellement tenu ?
                this.grabber = null;              // Main qui tient l'objet
                this.velocity = new THREE.Vector3(0, 0, 0); // Vélocité actuelle
                this.physicsEnabled = false;      // Physique activée ? (après premier grab)

                // === TAILLE POUR COLLISIONS ===
                this.halfHeight = 0.2; // Valeur par défaut

                // Calculer la vraie taille quand le modèle est chargé
                this.el.addEventListener('model-loaded', () => {
                    const box = new THREE.Box3().setFromObject(this.el.object3D);
                    const size = new THREE.Vector3();
                    box.getSize(size);
                    this.halfHeight = size.y / 2;
                });
            },

            /**
             * Attraper l'objet
             * @param {Element} hand - L'entité de la main qui attrape
             */
            grab: function (hand) {
                if (!this.data.enabled || this.isGrabbed) return;

                this.isGrabbed = true;
                this.grabber = hand;
                this.velocity.set(0, 0, 0); // Reset vélocité

                // Attacher l'objet à la main (suit automatiquement)
                hand.object3D.attach(this.el.object3D);

                // Émettre événement pour autres composants
                this.el.emit('grabbed', { hand: hand });
                console.log('[GRABBABLE] Objet attrapé');
            },

            /**
             * Relâcher l'objet
             * @param {THREE.Vector3} throwVelocity - Vélocité de lancer
             */
            release: function (throwVelocity) {
                if (!this.isGrabbed) return;

                // Récupérer position mondiale avant détachement
                const worldPos = new THREE.Vector3();
                this.el.object3D.getWorldPosition(worldPos);

                // Détacher de la main, rattacher à la scène
                this.el.sceneEl.object3D.attach(this.el.object3D);

                this.isGrabbed = false;
                this.grabber = null;
                this.physicsEnabled = true; // Activer physique après release

                // Appliquer vélocité de lancer
                if (throwVelocity) {
                    this.velocity.copy(throwVelocity).multiplyScalar(12);
                    console.log('[GRABBABLE] Lancé avec vélocité:',
                        this.velocity.x.toFixed(2),
                        this.velocity.y.toFixed(2),
                        this.velocity.z.toFixed(2));
                }

                this.el.emit('released');
            },

            /**
             * Mise à jour physique chaque frame
             */
            tick: function (time, delta) {
                // Pas de physique si attrapé ou pas encore lancé
                if (this.isGrabbed || !this.physicsEnabled) return;

                // Delta time en secondes (max 50ms pour éviter bugs)
                const dt = Math.min(delta / 1000, 0.05);
                const pos = this.el.object3D.position;

                // === GRAVITÉ ===
                this.velocity.y += this.data.gravity * dt;

                // === APPLIQUER VÉLOCITÉ ===
                pos.x += this.velocity.x * dt;
                pos.y += this.velocity.y * dt;
                pos.z += this.velocity.z * dt;

                // === COLLISION SOL ===
                const groundY = this.data.floorY + this.halfHeight;
                if (pos.y < groundY) {
                    pos.y = groundY;

                    // Rebond (inverser et réduire vélocité Y)
                    this.velocity.y *= -this.data.restitution;

                    // Friction au sol
                    this.velocity.x *= 0.9;
                    this.velocity.z *= 0.9;

                    // Arrêter si très lent
                    if (Math.abs(this.velocity.y) < 0.3) {
                        this.velocity.y = 0;
                    }
                }

                // === LIMITES DU MONDE ===
                const limit = 100;
                if (pos.x < -limit) { pos.x = -limit; this.velocity.x *= -0.5; }
                if (pos.x > limit) { pos.x = limit; this.velocity.x *= -0.5; }
                if (pos.z < -limit) { pos.z = -limit; this.velocity.z *= -0.5; }
                if (pos.z > limit) { pos.z = limit; this.velocity.z *= -0.5; }

                // === FRICTION AIR ===
                this.velocity.x *= 0.99;
                this.velocity.z *= 0.99;
            }
        });


        /* ============================================================
         * CONTRÔLES DE GRAB POUR MANETTE VR
         * Main droite - GRIP pour attraper/lancer
         * 
         * Fonctionnalités:
         * - Laser de visée (bleu → vert quand sur objet)
         * - Calcul de vélocité pour lancer
         * ============================================================ */
        AFRAME.registerComponent('grab-controls', {
            schema: {
                hand: { type: 'string', default: 'right' },    // Quelle main
                grabDistance: { type: 'number', default: 5 }   // Portée du grab
            },

            init: function () {
                // === ÉTAT ===
                this.grabbedObject = null;    // Objet actuellement tenu
                this.hoveredObject = null;    // Objet survolé par le laser
                this.isGrabbing = false;      // En train de tenir ?

                // === CALCUL DE VÉLOCITÉ POUR LANCER ===
                this.lastHandPos = new THREE.Vector3();
                this.currentHandPos = new THREE.Vector3();
                this.throwVelocity = new THREE.Vector3();
                this.smoothVelocity = new THREE.Vector3();

                // === RAYCASTER ===
                this.raycaster = new THREE.Raycaster();
                this.raycaster.far = this.data.grabDistance;

                // === CRÉATION DU LASER ===
                this.createLaser();

                // === ÉVÉNEMENTS MANETTE ===
                // GRIP uniquement (pour éviter conflit avec téléport sur trigger)
                const grabEvents = ['gripdown', 'squeezestart', 'abuttondown'];
                const releaseEvents = ['gripup', 'squeezeend', 'abuttonup'];

                grabEvents.forEach(evt => {
                    this.el.addEventListener(evt, () => {
                        console.log('[GRAB] Event:', evt);
                        this.tryGrab();
                    });
                });

                releaseEvents.forEach(evt => {
                    this.el.addEventListener(evt, () => {
                        console.log('[GRAB] Release:', evt);
                        this.release();
                    });
                });

                console.log('[GRAB] Initialisé pour main', this.data.hand);
            },

            /**
             * Crée le laser de visée
             */
            createLaser: function () {
                // Ligne du laser (cylindre fin)
                this.laser = document.createElement('a-entity');
                this.laser.setAttribute('geometry', {
                    primitive: 'cylinder',
                    radius: 0.003,
                    height: this.data.grabDistance,
                    segmentsRadial: 6
                });
                this.laser.setAttribute('material', {
                    color: '#00aaff',
                    opacity: 0.7,
                    shader: 'flat'
                });
                this.laser.setAttribute('position', '0 0 -' + (this.data.grabDistance / 2));
                this.laser.setAttribute('rotation', '90 0 0');
                this.el.appendChild(this.laser);

                // Point au bout du laser
                this.hitSphere = document.createElement('a-sphere');
                this.hitSphere.setAttribute('radius', '0.03');
                this.hitSphere.setAttribute('color', '#00aaff');
                this.hitSphere.setAttribute('material', 'shader: flat');
                this.hitSphere.setAttribute('position', '0 0 -' + this.data.grabDistance);
                this.el.appendChild(this.hitSphere);
            },

            /**
             * Tenter d'attraper un objet
             */
            tryGrab: function () {
                if (this.isGrabbing) return;

                if (this.hoveredObject) {
                    const grabbable = this.hoveredObject.components.grabbable;
                    if (grabbable) {
                        console.log('[GRAB] Objet attrapé!');
                        grabbable.grab(this.el);
                        this.grabbedObject = this.hoveredObject;
                        this.isGrabbing = true;

                        // Masquer le laser
                        this.laser.setAttribute('visible', false);
                        this.hitSphere.setAttribute('visible', false);

                        // Initialiser tracking vélocité
                        this.el.object3D.getWorldPosition(this.lastHandPos);
                        this.smoothVelocity.set(0, 0, 0);
                    }
                } else {
                    console.log('[GRAB] Aucun objet à attraper');
                }
            },

            /**
             * Relâcher l'objet tenu
             */
            release: function () {
                if (!this.isGrabbing) return;

                if (this.grabbedObject) {
                    const grabbable = this.grabbedObject.components.grabbable;
                    if (grabbable) {
                        // Passer la vélocité calculée
                        grabbable.release(this.smoothVelocity.clone());
                    }
                }

                this.grabbedObject = null;
                this.isGrabbing = false;

                // Réafficher le laser
                this.laser.setAttribute('visible', true);
                this.hitSphere.setAttribute('visible', true);
            },

            /**
             * Mise à jour chaque frame
             */
            tick: function (time, delta) {
                // === SI ON TIENT UN OBJET: CALCULER VÉLOCITÉ ===
                if (this.isGrabbing) {
                    this.el.object3D.getWorldPosition(this.currentHandPos);

                    // Vélocité instantanée
                    this.throwVelocity.subVectors(this.currentHandPos, this.lastHandPos);

                    // Lissage (moyenne glissante)
                    this.smoothVelocity.lerp(this.throwVelocity, 0.4);

                    this.lastHandPos.copy(this.currentHandPos);
                    return;
                }

                // === MISE À JOUR DU RAYCASTER ===
                const pos = new THREE.Vector3();
                const dir = new THREE.Vector3(0, 0, -1);

                this.el.object3D.getWorldPosition(pos);
                dir.applyQuaternion(this.el.object3D.quaternion);

                this.raycaster.set(pos, dir.normalize());

                // === CHERCHER LES OBJETS GRABBABLES ===
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

                // === MISE À JOUR VISUELLE DU LASER ===
                if (closest && closestDist <= this.data.grabDistance) {
                    this.hoveredObject = closest;
                    // Laser vert = objet ciblé
                    this.laser.setAttribute('material', 'color', '#00ff00');
                    this.hitSphere.setAttribute('color', '#00ff00');
                    this.laser.setAttribute('geometry', 'height', closestDist);
                    this.laser.setAttribute('position', '0 0 -' + (closestDist / 2));
                    this.hitSphere.setAttribute('position', '0 0 -' + closestDist);
                } else {
                    this.hoveredObject = null;
                    // Laser bleu = rien
                    this.laser.setAttribute('material', 'color', '#00aaff');
                    this.hitSphere.setAttribute('color', '#00aaff');
                    this.laser.setAttribute('geometry', 'height', this.data.grabDistance);
                    this.laser.setAttribute('position', '0 0 -' + (this.data.grabDistance / 2));
                    this.hitSphere.setAttribute('position', '0 0 -' + this.data.grabDistance);
                }
            }
        });


        /* ============================================================
         * STOPPER L'ARAIGNÉE QUAND ATTRAPÉE
         * Pause le déplacement pendant le grab
         * ============================================================ */
        AFRAME.registerComponent('stoppable-on-grab', {
            init: function () {
                // Quand attrapé → pause
                this.el.addEventListener('grabbed', () => {
                    const walker = this.el.components['spider-walker'];
                    if (walker) walker.paused = true;
                });

                // Quand relâché → reprendre + nouvelle position de départ
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
    <!-- ============================================================
         SCÈNE A-FRAME
         ============================================================ -->
    <a-scene fog="type: exponential; color: #c9a66b; density: 0.025">

        <!-- ========== ASSETS (MODÈLES 3D) ========== -->
        <a-assets>
            <!-- Statues et monuments -->
            <a-asset-item id="sphynx" src="../assets/modelAvatar/sphynx.glb"></a-asset-item>
            <a-asset-item id="anubis" src="../assets/modelAvatar/Anubis Statue.glb"></a-asset-item>
            <a-asset-item id="pyramid" src="../assets/modelAvatar/Pyramid.glb"></a-asset-item>
            <a-asset-item id="roman_temple" src="../assets/modelAvatar/low_poly_roman_temple_wip.glb"></a-asset-item>

            <!-- Animaux -->
            <a-asset-item id="camel_walk" src="../assets/modelAvatar/camel-walk.glb"></a-asset-item>
            <a-asset-item id="spider"
                src="../assets/modelAvatar/animated_low-poly_spider_game-ready.glb"></a-asset-item>
            <a-asset-item id="scorpion" src="../assets/modelAvatar/scorpion.glb"></a-asset-item>

            <!-- Objets interactifs (GRABBABLE) -->
            <a-asset-item id="coin" src="../assets/modelAvatar/lowpoly_gold_coin.glb"></a-asset-item>
            <a-asset-item id="stone_pickaxe" src="../assets/modelAvatar/Stone Pickaxe.glb"></a-asset-item>

            <!-- Décors -->
            <a-asset-item id="arch" src="../assets/modelAvatar/Arch.glb"></a-asset-item>
            <a-asset-item id="fence" src="../assets/modelAvatar/Fence Pillar.glb"></a-asset-item>
            <a-asset-item id="tent" src="../assets/modelAvatar/Tent.glb"></a-asset-item>
            <a-asset-item id="chest_glb" src="../assets/modelAvatar/chest.glb"></a-asset-item>
            <a-asset-item id="chest_gold" src="../assets/modelAvatar/Chest Gold.glb"></a-asset-item>
            <a-asset-item id="chest_1" src="../assets/modelAvatar/Chest (1).glb"></a-asset-item>
            <a-asset-item id="coffin" src="../assets/modelAvatar/Coffin.glb"></a-asset-item>
            <a-asset-item id="sarcophagus" src="../assets/modelAvatar/stone_sarcophagi_cairo_museum.glb"></a-asset-item>
            <a-asset-item id="bear_trap" src="../assets/modelAvatar/Bear Trap.glb"></a-asset-item>
            <a-asset-item id="torture_device" src="../assets/modelAvatar/Torture Device.glb"></a-asset-item>
            <a-asset-item id="spade" src="../assets/modelAvatar/Spade.glb"></a-asset-item>
            <a-asset-item id="trap_door" src="../assets/modelAvatar/Trap Door.glb"></a-asset-item>
        </a-assets>

        <!-- ========== SOL TÉLÉPORTABLE ========== 
             IMPORTANT: Cette entité invisible permet la téléportation
             La classe "teleportable" est utilisée par teleport-controls-custom -->
        <a-plane class="teleportable" rotation="-90 0 0" width="200" height="200" position="0 0.01 0" visible="false"
            material="opacity: 0">
        </a-plane>

        <!-- ========== ENVIRONNEMENT ========== -->
        <a-entity
            environment="preset: egypt; groundYScale: 6; fog: 0; skyColor: #c9a66b; horizonColor: #b89968;"></a-entity>

        <!-- Lumières -->
        <a-entity light="type: ambient; intensity: 0.7; color: #f4d4a8"></a-entity>
        <a-entity light="type: directional; intensity: 0.4; color: #e6c288" position="1 1 0"></a-entity>

        <!-- ========== MONUMENTS ========== -->
        <!-- Sphinx (4x) -->
        <a-entity gltf-model="#sphynx" position="-14.976 5.313 21.367" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#sphynx" position="-27.970 5.313 21.367" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#sphynx" position="-27.970 5.313 -38.222" scale="1.5 1.5 1.5"
            rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#sphynx" position="-15.029 5.313 -38.222" scale="1.5 1.5 1.5"
            rotation="0 180 0"></a-entity>

        <!-- Temples romains (4x) -->
        <a-entity gltf-model="#roman_temple" position="-15 2.6 23" scale="7.5 7.5 7.5" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#roman_temple" position="-28 2.6 23" scale="7.5 7.5 7.5" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#roman_temple" position="-15 2.6 -40" scale="7.5 7.5 7.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#roman_temple" position="-28 2.6 -40" scale="7.5 7.5 7.5" rotation="0 0 0"></a-entity>

        <!-- Pyramide -->
        <a-entity gltf-model="#pyramid" position="35 -10 -7" scale="8 8 8" rotation="0 133 0"></a-entity>

        <!-- Statues Anubis -->
        <a-entity gltf-model="#anubis" position="5 3.034 -2" scale="0.5 0.5 0.5" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#anubis" position="5 3.034 -20" scale="0.5 0.5 0.5" rotation="0 180 0"></a-entity>

        <!-- ========== ANIMAUX ANIMÉS ========== -->
        <!-- Chameau qui marche -->
        <a-entity gltf-model="#camel_walk" position="0.577 1.636 -42.65" scale="0.05 0.05 0.05" camel-animator
            camel-walker="distance: 50; speed: 1">
        </a-entity>

        <!-- Araignées (GRABBABLE + animation + déplacement) -->
        <a-entity gltf-model="#spider" position="5 0.1 -5" scale="0.03 0.03 0.03" spider-animator
            spider-walker="speed: 0.8; radius: 15; changeInterval: 2000" grabbable="restitution: 0.3" stoppable-on-grab>
        </a-entity>
        <a-entity gltf-model="#spider" position="-8 0.1 -3" scale="0.02 0.02 0.02" spider-animator
            spider-walker="speed: 0.5; radius: 12; changeInterval: 3500" grabbable="restitution: 0.3" stoppable-on-grab>
        </a-entity>
        <a-entity gltf-model="#spider" position="3 0.1 8" scale="0.025 0.025 0.025" spider-animator
            spider-walker="speed: 0.6; radius: 10; changeInterval: 2500" grabbable="restitution: 0.3" stoppable-on-grab>
        </a-entity>
        <a-entity gltf-model="#spider" position="-5 0.1 10" scale="0.04 0.04 0.04" spider-animator
            spider-walker="speed: 0.4; radius: 8; changeInterval: 4000" grabbable="restitution: 0.3" stoppable-on-grab>
        </a-entity>

        <!-- Scorpion (GRABBABLE) -->
        <a-entity gltf-model="#scorpion" position="0 0.1 5" scale="0.3 0.3 0.3" grabbable="restitution: 0.4"></a-entity>

        <!-- ========== OBJETS GRABBABLE ========== -->
        <!-- Pièces d'or -->
        <a-entity gltf-model="#coin" position="2 0.2 2" scale="0.25 0.25 0.25" grabbable="restitution: 0.7"></a-entity>
        <a-entity gltf-model="#coin" position="-59.820 1.0 -6.4" scale="0.25 0.25 0.25"
            grabbable="restitution: 0.7"></a-entity>
        <a-entity gltf-model="#coin" position="-59.820 1.1 -6.4" scale="0.25 0.25 0.25"
            grabbable="restitution: 0.7"></a-entity>
        <a-entity gltf-model="#coin" position="-59.820 1.2 -6.4" scale="0.25 0.25 0.25"
            grabbable="restitution: 0.7"></a-entity>

        <!-- Pioches -->
        <a-entity gltf-model="#stone_pickaxe" position="4.966 0.5 2.221" scale="0.5 0.5 0.5" rotation="90 0 -71"
            grabbable="restitution: 0.2"></a-entity>
        <a-entity gltf-model="#stone_pickaxe" position="3.090 0.5 2.221" scale="0.5 0.5 0.5" rotation="90 0 100"
            grabbable="restitution: 0.2"></a-entity>

        <!-- ========== DÉCORS ========== -->
        <!-- Arches -->
        <a-entity gltf-model="#arch" position="4 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#arch" position="2 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#arch" position="0 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#arch" position="-2 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>

        <!-- Clôtures -->
        <a-entity gltf-model="#fence" position="-4 0 -13" scale="1.5 1.5 1.5"></a-entity>
        <a-entity gltf-model="#fence" position="-4 0 -7" scale="1.5 1.5 1.5"></a-entity>

        <!-- Tente -->
        <a-entity gltf-model="#tent" position="-17.268 1.986 -9.3" scale="2.5 2.5 2.5" rotation="0 180 0"></a-entity>

        <!-- Coffres -->
        <a-entity gltf-model="#chest_glb" position="38.821 0.943 -4" scale="0.3 0.3 0.3" rotation="0 -90 0"></a-entity>
        <a-entity gltf-model="#chest_gold" position="-16.659 0.296 -7.821" scale="0.5 0.5 0.5"
            rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#chest_1" position="-15 0.5 25" scale="0.8 0.8 0.8" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#chest_1" position="-28 0.5 25" scale="0.8 0.8 0.8" rotation="0 180 0"></a-entity>

        <!-- Cercueils -->
        <a-entity gltf-model="#coffin" position="-60 0 0" scale="1 1 1"></a-entity>
        <a-entity gltf-model="#coffin" position="-56.711 0 0" scale="1 1 1"></a-entity>
        <a-entity gltf-model="#coffin" position="-56.711 0 4.535" scale="1 1 1"></a-entity>

        <!-- Sarcophage -->
        <a-entity gltf-model="#sarcophagus" position="-56 -15.7 -15" scale="1 1 1" rotation="0 90 0"></a-entity>

        <!-- Pièges -->
        <a-entity gltf-model="#bear_trap" position="-15 0.5 25" scale="0.8 0.8 0.8"></a-entity>
        <a-entity gltf-model="#bear_trap" position="-28 0.5 25" scale="0.8 0.8 0.8"></a-entity>

        <!-- ========== RIG VR OCULUS QUEST ========== 
             Configuration:
             - Main droite: GRIP = attraper/lancer
             - Main gauche: TRIGGER = téléportation -->
        <a-entity id="rig" position="-18 0 -9">
            <a-camera id="camera" position="0 1.6 0" look-controls wasd-controls="enabled: true"></a-camera>

            <a-entity id="rhand" oculus-touch-controls="hand: right; model: false"
                grab-controls="hand: right; grabDistance: 5">
                <a-box color="#2266ff" width="0.04" height="0.02" depth="0.1" position="0 0 -0.03"></a-box>
            </a-entity>

            <a-entity id="lhand" oculus-touch-controls="hand: left; model: false" teleport-controls="cameraRig: #rig; 
                           teleportOrigin: #camera; 
                           button: trigger; 
                           collisionEntities: .teleportable; 
                           type: parabolic; 
                           curveShootingSpeed: 10; 
                           hitCylinderColor: #ff9900; 
                           curveColor: #ff9900; 
                           curveNumberPoints: 40;">
                <a-box color="#ff6622" width="0.04" height="0.02" depth="0.1" position="0 0 -0.03"></a-box>
            </a-entity>
        </a-entity>
    </a-scene>
</body>

</html>