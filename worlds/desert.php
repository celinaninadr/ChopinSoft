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
    <script
        src="https://cdn.jsdelivr.net/npm/aframe-environment-component@1.3.7/dist/aframe-environment-component.min.js"></script>
</head>

<body>
    <a-scene>
        <a-plane class="teleportable" rotation="-90 0 0" width="200" height="200" position="0 0.01 0" visible="false"
            material="opacity: 0"></a-plane>

        <a-entity id="rig" rotation="0 0 0">
            <a-entity camera position="-18 2.8 -9" wasd-controls look-controls></a-entity>
            <?php if ($modelAvatar): ?>
                <a-entity gltf-model="#avatar" position="0 0 0" scale="1 1 1" rotation="0 0 0"></a-entity>
            <?php endif; ?>
        </a-entity>
        <!-- Coffre près de l'entrée -->
        <a-entity gltf-model="#chest_glb" position="-57 0 -4" scale="0.25 0.25 0.25" rotation="0 90 0"></a-entity>

        <a-entity environment="preset: egypt; groundYScale: 6; fog: 0.7"></a-entity>
        <a-entity light="type:ambient;intensity:1.0"></a-entity>

        <a-entity gltf-model="#sphynx" position="-14.724 10.495 20" scale="1.5 1.5 1.5" rotation="0 30 0">
        </a-entity>
        <a-entity gltf-model="#camel" position="10 0 0" scale="1 1 1" rotation="0 90 0"> </a-entity>
        <a-entity gltf-model="#anubis" position="5 3.034 -2" scale="0.5 0.5 0.5" rotation="0 180 0"> </a-entity>
        <a-entity gltf-model="#anubis" position="5 3.034 -20" scale="0.5 0.5 0.5" rotation="0 180 0"> </a-entity>

        <a-entity gltf-model="#arch" position="4 0 -10" scale="1 1 1" rotation="0 90 0"> </a-entity>
        <a-entity gltf-model="#arch" position="2 0 -10" scale="1 1 1" rotation="0 90 0"> </a-entity>
        <a-entity gltf-model="#arch" position="0 0 -10" scale="1 1 1" rotation="0 90 0"> </a-entity>
        <a-entity gltf-model="#arch" position="-2 0 -10" scale="1 1 1" rotation="0 90 0"> </a-entity>

        <a-entity gltf-model="#fence" position="-4 0 -13" scale="1.5 1.5 1.5" rotation="0 0 0"> </a-entity>
        <a-entity gltf-model="#fence" position="-4 0 -7" scale="1.5 1.5 1.5" rotation="0 0 0"> </a-entity>
        <a-entity gltf-model="#coin" position="2 0 2" scale="0.25 0.25 0.25" rotation="0 0 0"> </a-entity>

        <a-entity gltf-model="#pyramid" position="35 -10 -7" scale="8 8 8" rotation="0 133 0"> </a-entity>
        <a-entity gltf-model="#roman_temple" position="-15 2.6 23" scale="7.5 7.5 7.5" rotation="0 180 0"> </a-entity>
        <a-entity gltf-model="#roman_temple" position="-28 5 23" scale="7.5 7.5 7.5" rotation="0 180 0"> </a-entity>
        <a-entity gltf-model="#roman_temple" position="-15 5 -40" scale="7.5 7.5 7.5" rotation="0 360 0"> </a-entity>
        <a-entity gltf-model="#roman_temple" position="-28 5 -40" scale="7.5 7.5 7.5" rotation="0 360 0"> </a-entity>

        <a-entity gltf-model="#sarcophagus" position="-56 -15,7  -15" scale="1 1 1" rotation="0 90 0"> </a-entity>

        <!-- Rig VR avec caméra et contrôleurs -->
        <a-entity id="rig" position="0 0 0">
            <a-camera id="camera" position="0 1.6 0" look-controls wasd-controls="enabled: false"></a-camera>

            <!-- Main droite -->
            <a-entity id="rhand" laser-controls="hand: right"></a-entity>

            <!-- Main gauche avec téléportation -->
            <a-entity id="lhand" laser-controls="hand: left"
                teleport-controls="cameraRig: #rig; teleportOrigin: #camera; button: trigger; curveShootingSpeed: 15; landingMaxAngle: 90; collisionEntities: .teleportable; type: parabolic">
            </a-entity>
        </a-entity>

        <!-- Tente -->
        <a-entity gltf-model="#tent" position="-17.268 2.448 -9.3" scale="2.5105 2.5105 2.5105"
            rotation="0 180 0"></a-entity>

        <!-- Stone Pickaxe -->
        <a-entity gltf-model="#stone_pickaxe" position="-55 0.5 -4" scale="0.5 0.5 0.5" rotation="0 0 0"></a-entity>


        <!-- Roman Temple -->
        <a-entity gltf-model="#roman_temple_main" position="1.000 0 -45.007" scale="0.0085 0.0085 0.0085"
            rotation="0 360 0"></a-entity>

        <a-assets>
            <a-asset-item id="sphynx" src="../assets/modelAvatar/sphynx.glb"></a-asset-item>
            <a-asset-item id="camel"
                src="../assets/modelAvatar/low_poly_western_camel_camelops_hesternus.glb"></a-asset-item>
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
            <?php if ($modelAvatar): ?>
                <a-asset-item id="avatar" src="../<?php echo htmlspecialchars($modelAvatar); ?>"></a-asset-item>
            <?php endif; ?>
        </a-assets>
    </a-scene>
</body>

</html>