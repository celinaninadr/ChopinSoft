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
        <a-entity id="rig" rotation="0 0 0">
            <a-entity camera position="-18 2.8 -9" wasd-controls look-controls></a-entity>
            <?php if ($modelAvatar): ?>
                <a-entity gltf-model="#avatar" position="0 0 0" scale="1 1 1" rotation="0 0 0"></a-entity>
            <?php endif; ?>
        </a-entity>
        <!-- Coffre près de l'entrée -->
        <a-entity gltf-model="#chest_glb" position="-57 0 -4" scale="0.5 0.5 0.5" rotation="0 90 0"></a-entity>

        <a-entity environment="preset: egypt; groundYScale: 6; fog: 0.7"></a-entity>
        <a-entity light="type:ambient;intensity:1.0"></a-entity>

        <a-entity gltf-model="#sphynx" position="-14.724 10.495 20" scale="3.000 3.000 3.000" rotation="0 30 0"> </a-entity>
        <a-entity gltf-model="#camel" position="10 0 0" scale="2 2 2" rotation="0 90 0"> </a-entity>
        <a-entity gltf-model="#anubis" position="5 6 -2" scale="1 1 1" rotation="0 180 0"> </a-entity>
        <a-entity gltf-model="#anubis" position="5 6 -20" scale="1 1 1" rotation="0 180 0"> </a-entity>

        <a-entity gltf-model="#arch" position="4 0 -10" scale="2 2 2" rotation="0 90 0"> </a-entity>
        <a-entity gltf-model="#arch" position="2 0 -10" scale="2 2 2" rotation="0 90 0"> </a-entity>
        <a-entity gltf-model="#arch" position="0 0 -10" scale="2 2 2" rotation="0 90 0"> </a-entity>
        <a-entity gltf-model="#arch" position="-2 0 -10" scale="2 2 2" rotation="0 90 0"> </a-entity>

        <a-entity gltf-model="#fence" position="-4 0 -13" scale="3 3 3" rotation="0 0 0"> </a-entity>
        <a-entity gltf-model="#fence" position="-4 0 -7" scale="3 3 3" rotation="0 0 0"> </a-entity>
        <a-entity gltf-model="#coin" position="2 0 2" scale="0.5 0.5 0.5" rotation="0 0 0"> </a-entity>

        <a-entity gltf-model="#pyramid" position="35 -10 -7" scale="8 8 8" rotation="0 133 0"> </a-entity>
        <a-entity gltf-model="#roman_temple" position="-15 5 23" scale="15 15 15" rotation="0 180 0"> </a-entity>
        <a-entity gltf-model="#roman_temple" position="-28 5 23" scale="15 15 15" rotation="0 180 0"> </a-entity>
        <a-entity gltf-model="#roman_temple" position="-15 5 -40" scale="15 15 15" rotation="0 360 0"> </a-entity>
        <a-entity gltf-model="#roman_temple" position="-28 5 -40" scale="15 15 15" rotation="0 360 0"> </a-entity>

        <a-entity gltf-model="#sarcophagus" position="-56 -15,7  -15" scale="2 2 2" rotation="0 90 0"> </a-entity>

        <!-- Main -->
        <a-entity id="rig">
            <a-camera id="camera" position="0 1.6 0"></a-camera>
            <a-entity id="rhand" hand hand-controls="hand: right"></a-entity>
            <a-entity id="lhand" hand teleport-controls="cameraRig: #rig; teleportOrigin: #camera; 
button: trigger" hand-controls="hand: left"></a-entity>
        </a-entity>

        <!-- Tente -->
        <a-entity gltf-model="#tent" position="-17.268 2.448 -9.3" scale="5.021 5.021 5.021"
            rotation="0 180 0"></a-entity>

        <!-- Camels Respite -->
        <a-entity gltf-model="#camels_respite" position="-54.908 0.266 32.358" scale="0.5 0.5 0.5" rotation="0.000 135.204 0.000"></a-entity>

        <!-- Temple Entrance -->
        <a-entity gltf-model="#temple_entrance" position="-30 10 5" scale="0.1 0.1 0.1" rotation="0 0 0"></a-entity>

        <!-- Roman Temple -->
        <a-entity gltf-model="#roman_temple_main" position="1.000 0 -45.007" scale="0.017 0.017 0.017" rotation="0 360 0"></a-entity>

        <!-- Oasis Trading Post -->
        <a-entity gltf-model="#oasis_trading_post" position="-45.058 6.540 -36.086" scale="17.894 17.894 17.894" rotation="0 134.529 0"></a-entity>

        <!-- Aztec Style Temple Kit -->
        <a-entity gltf-model="#aztec_style_temple_kit" position="26.793 1.627 54.707" scale="12.244 12.244 12.244" rotation="0 45 0"></a-entity>

        <!-- Groupe pyramide (positionné en -50 0 -7) et tourné de 90° autour de Y -->
        <a-entity id="pyramid-group" position="-50 0 -7" rotation="0 270 0">
            <a-triangle vertex-a="0 20 0" vertex-b="-10 0,7 -10" vertex-c="-3 0,7 -10"
                material="color: #e9d25b; side:double"></a-triangle>
            <a-triangle vertex-a="0 20 0" vertex-b="3 0,7 -10" vertex-c="10 0,7 -10"
                material="color: #e9d25b; side:double"></a-triangle>

            <a-triangle vertex-a="0 20 0" vertex-b="10 0,7 -10" vertex-c="10 0,7 10"
                material="color: #e9d25b; side:double"></a-triangle>
            <a-triangle vertex-a="0 20 0" vertex-b="10 0,7 10" vertex-c="-10 0,7 10"
                material="color: #e9d25b; side:double"></a-triangle>
            <a-triangle vertex-a="0 20 0" vertex-b="-10 0,7 10" vertex-c="-10 0,7 -10"
                material="color: #e9d25b; side:double"></a-triangle>

            <a-plane position="0 0,9 0" rotation="-90 0 0" width="20" height="20"
                material="color: #e9d25b; side:double"></a-plane>

            <!-- Modèle GLB (aligné au groupe) -->
            <a-entity gltf-model="#piramid" position="0 1 0" scale="8 8 8" rotation="0 0 0"></a-entity>
        </a-entity>

        <a-assets>
            <a-asset-item id="sphynx" src="../assets/modelAvatar/sphynx.glb"></a-asset-item>
            <a-asset-item id="chest"
                src="../assets/modelAvatar/free_animated_low_poly_cartoon_chest_kit.glb"></a-asset-item>
            <a-asset-item id="camel"
                src="../assets/modelAvatar/low_poly_western_camel_camelops_hesternus.glb"></a-asset-item>
            <a-asset-item id="anubis" src="../assets/modelAvatar/Anubis Statue.glb"></a-asset-item>
            <a-asset-item id="arch" src="../assets/modelAvatar/Arch.glb"></a-asset-item>
            <a-asset-item id="fence" src="../assets/modelAvatar/Fence Pillar.glb"></a-asset-item>
            <a-asset-item id="coin" src="../assets/modelAvatar/lowpoly_gold_coin.glb"></a-asset-item>

            <?php if ($modelAvatar): ?>
                <a-asset-item id="avatar" src="../<?php echo htmlspecialchars($modelAvatar); ?>"></a-asset-item>
            <?php endif; ?>
        </a-assets>
    </a-scene>
</body>

</html>