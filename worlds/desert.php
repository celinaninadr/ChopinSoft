<?php
session_start();

if (empty($_SESSION['user'])) {
    header('Location: /ChopinSoft/index.php?route=user/login');
    exit;
}

if (($_SESSION['play']['idWorld'] ?? 0) !== 1) {
    header('Location: /ChopinSoft/index.php?route=home/index');
    exit;
}

$modelAvatar = $_GET['modelAvatar'] ?? '';
$username = $_GET['username'] ?? '';
$idAvatar = $_GET['idAvatar'] ?? '';

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Environnement désert</title>
    <meta name="description" content="Environnement desert" />
    <script src="https://aframe.io/releases/1.6.0/aframe.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aframe-environment-component@1.3.7/dist/aframe-environment-component.min.js"></script>

    <script>
        /**
         * Composant de téléportation custom compatible A-Frame 1.6.0
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
                
                this.el.addEventListener(this.data.button + 'down', this.onButtonDown.bind(this));
                this.el.addEventListener(this.data.button + 'up', this.onButtonUp.bind(this));
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
    </script>
</head>

<body>
    <a-scene>
        <!-- Assets EN PREMIER -->
        <a-assets>
            <a-asset-item id="sphynx" src="../assets/modelAvatar/sphynx.glb"></a-asset-item>
            <a-asset-item id="camel" src="../assets/modelAvatar/low_poly_western_camel_camelops_hesternus.glb"></a-asset-item>
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
            <?php if ($modelAvatar): ?>
                <a-asset-item id="avatar" src="../<?php echo htmlspecialchars($modelAvatar); ?>"></a-asset-item>
            <?php endif; ?>
        </a-assets>

        <!-- Sol invisible pour téléportation -->
        <a-plane class="teleportable" rotation="-90 0 0" width="200" height="200" position="0 0.01 0" visible="false" material="opacity: 0"></a-plane>

        <!-- Environnement et lumière -->
        <a-entity environment="preset: egypt; groundYScale: 6; fog: 0.7"></a-entity>
        <a-entity light="type:ambient;intensity:1.0"></a-entity>

        <!-- Sphinx -->
        <a-entity gltf-model="#sphynx" position="-14.976 5.313 21.367" scale="1.5 1.5 1.5" rotation="0 -0.976 0"></a-entity>
        <a-entity gltf-model="#sphynx" position="-27.970 5.313 21.367" scale="1.5 1.5 1.5" rotation="0 -0.976 0"></a-entity>
        <a-entity gltf-model="#sphynx" position="-27.970 5.313 -38.222" scale="1.5 1.5 1.5" rotation="0 180.000 0"></a-entity>
        <a-entity gltf-model="#sphynx" position="-15.029 5.313 -38.222" scale="1.5 1.5 1.5" rotation="0 180.000 0"></a-entity>

        <!-- Animaux et statues -->
        <a-entity gltf-model="#camel" position="10 0 0" scale="1 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#anubis" position="5 3.034 -2" scale="0.5 0.5 0.5" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#anubis" position="5 3.034 -20" scale="0.5 0.5 0.5" rotation="0 180 0"></a-entity>

        <!-- Arches -->
        <a-entity gltf-model="#arch" position="4 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#arch" position="2 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#arch" position="0 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>
        <a-entity gltf-model="#arch" position="-2 0 -10" scale="1 1 1" rotation="0 90 0"></a-entity>

        <!-- Clôtures et objets -->
        <a-entity gltf-model="#fence" position="-4 0 -13" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#fence" position="-4 0 -7" scale="1.5 1.5 1.5" rotation="0 0 0"></a-entity>
        <a-entity gltf-model="#coin" position="2 0 2" scale="0.25 0.25 0.25" rotation="0 0 0"></a-entity>

        <!-- Structures principales -->
        <a-entity gltf-model="#pyramid" position="35 -10 -7" scale="8 8 8" rotation="0 133 0"></a-entity>
        <a-entity gltf-model="#roman_temple" position="-15 2.6 23" scale="7.5 7.5 7.5" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#roman_temple" position="-28 2.6 23" scale="7.5 7.5 7.5" rotation="0 180 0"></a-entity>
        <a-entity gltf-model="#roman_temple" position="-15 2.6 -40" scale="7.5 7.5 7.5" rotation="0 360 0"></a-entity>
        <a-entity gltf-model="#roman_temple" position="-28 2.6 -40" scale="7.5 7.5 7.5" rotation="0 360 0"></a-entity>

        <a-entity gltf-model="#sarcophagus" position="-56 -15.7 -15" scale="1 1 1" rotation="0 90 0"></a-entity>

        <!-- Coffre près de l'entrée -->
        <a-entity gltf-model="#chest_glb" position="38.821 0.943 -4" scale="0.300 0.300 0.300" rotation="0 -90.873 0"></a-entity>

        <!-- Tente -->
        <a-entity gltf-model="#tent" position="-17.268 1.986 -9.3" scale="2.5105 2.5105 2.5105" rotation="0 180 0"></a-entity>

        <!-- Stone Pickaxe -->
        <a-entity gltf-model="#stone_pickaxe" position="-55 0.5 -4" scale="0.5 0.5 0.5" rotation="0 0 0"></a-entity>

        <!-- Mayan Ziggurat -->
        <a-entity gltf-model="#step_pyramid" position="63.247 12.586 -65.459" scale="70 70 70" rotation="0 0 0"></a-entity>

        <!-- Roman Temple principal -->
        <a-entity gltf-model="#roman_temple_main" position="1.000 0 -45.007" scale="0.0085 0.0085 0.0085" rotation="0 360 0"></a-entity>

        <!-- Rig VR avec caméra et contrôleurs -->
        <a-entity id="rig" position="-18 0 -9">
            <a-camera id="camera" position="0 1.6 0" look-controls wasd-controls="enabled: true"></a-camera>

            <?php if ($modelAvatar): ?>
                <a-entity gltf-model="#avatar" position="0 0 0" scale="1 1 1" rotation="0 0 0"></a-entity>
            <?php endif; ?>

            <!-- Main droite -->
            <a-entity id="rhand" oculus-touch-controls="hand: right"></a-entity>

            <!-- Main gauche avec téléportation -->
            <a-entity id="lhand" 
                oculus-touch-controls="hand: left"
                teleport-controls-custom="cameraRig: #rig; teleportOrigin: #camera; collisionEntities: .teleportable; button: trigger; curveShootingSpeed: 15">
            </a-entity>
        </a-entity>
    </a-scene>
</body>

</html>