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
        <a-entity gltf-model="#chest_glb" position="-57 0 -4" scale="0.25 0.25 0.25" rotation="0 90 0"></a-entity>

        <a-entity environment="preset: egypt; groundYScale: 6; fog: 0.7"></a-entity>
        <a-entity light="type:ambient;intensity:1.0"></a-entity>

        <a-entity gltf-model="#sphynx" position="-14.724 10.495 20" scale="1.5 1.5 1.5" rotation="0 30 0">
        </a-entity>
        <a-entity gltf-model="#camel" position="10 0 0" scale="1 1 1" rotation="0 90 0"> </a-entity>
        <a-entity gltf-model="#anubis" position="5 6 -2" scale="0.5 0.5 0.5" rotation="0 180 0"> </a-entity>
        <a-entity gltf-model="#anubis" position="5 6 -20" scale="0.5 0.5 0.5" rotation="0 180 0"> </a-entity>

        <a-entity gltf-model="#arch" position="4 0 -10" scale="1 1 1" rotation="0 90 0"> </a-entity>
        <a-entity gltf-model="#arch" position="2 0 -10" scale="1 1 1" rotation="0 90 0"> </a-entity>
        <a-entity gltf-model="#arch" position="0 0 -10" scale="1 1 1" rotation="0 90 0"> </a-entity>
        <a-entity gltf-model="#arch" position="-2 0 -10" scale="1 1 1" rotation="0 90 0"> </a-entity>

        <a-entity gltf-model="#fence" position="-4 0 -13" scale="1.5 1.5 1.5" rotation="0 0 0"> </a-entity>
        <a-entity gltf-model="#fence" position="-4 0 -7" scale="1.5 1.5 1.5" rotation="0 0 0"> </a-entity>
        <a-entity gltf-model="#coin" position="2 0 2" scale="0.25 0.25 0.25" rotation="0 0 0"> </a-entity>

        <a-entity gltf-model="#pyramid" position="35 -10 -7" scale="4 4 4" rotation="0 133 0"> </a-entity>
        <a-entity gltf-model="#roman_temple" position="-15 5 23" scale="7.5 7.5 7.5" rotation="0 180 0"> </a-entity>
        <a-entity gltf-model="#roman_temple" position="-28 5 23" scale="7.5 7.5 7.5" rotation="0 180 0"> </a-entity>
        <a-entity gltf-model="#roman_temple" position="-15 5 -40" scale="7.5 7.5 7.5" rotation="0 360 0"> </a-entity>
        <a-entity gltf-model="#roman_temple" position="-28 5 -40" scale="7.5 7.5 7.5" rotation="0 360 0"> </a-entity>

        <a-entity gltf-model="#sarcophagus" position="-56 -15,7  -15" scale="1 1 1" rotation="0 90 0"> </a-entity>

        <!-- Main et caméra VR -->
        <a-entity id="rig">
            <a-camera id="camera" position="0 1.6 0"></a-camera>
            <a-entity id="rhand" hand-controls="hand: right"></a-entity>
            <a-entity id="lhand" hand-controls="hand: left"
                teleport-controls="cameraRig: #rig; teleportOrigin: #camera; button: trigger; curveShootingSpeed: 10; type: parabolic">
            </a-entity>
        </a-entity>

        <!-- Tente -->
        <a-entity gltf-model="#tent" position="-17.268 2.448 -9.3" scale="2.5105 2.5105 2.5105"
            rotation="0 180 0"></a-entity>

        <!-- Camels Respite -->
<<<<<<< HEAD
        <a-entity gltf-model="#camels_respite" position="-54.908 0.266 32.358" scale="0.25 0.25 0.25"
            rotation="0.000 135.204 0.000"></a-entity>
=======
        <a-entity gltf-model="#camels_respite" position="-54.908 0.266 32.358" scale="0.5 0.5 0.5" rotation="0.000 135.204 0.000"></a-entity>

        <!-- Temple Entrance -->
        <a-entity gltf-model="#temple_entrance" position="-30 10 5" scale="0.1 0.1 0.1" rotation="0 0 0"></a-entity>

        <!-- Roman Temple -->
        <a-entity gltf-model="#roman_temple_main" position="1.000 0 -45.007" scale="0.017 0.017 0.017" rotation="0 360 0"></a-entity>

        <!-- Oasis Trading Post -->
        <a-entity gltf-model="#oasis_trading_post" position="-45.058 6.540 -36.086" scale="17.894 17.894 17.894" rotation="0 134.529 0"></a-entity>

        <!-- Aztec Style Temple Kit -->
        <a-entity gltf-model="#aztec_style_temple_kit" position="26.793 1.627 54.707" scale="12.244 12.244 12.244" rotation="0 45 0"></a-entity>
>>>>>>> 25674d7752907d8e03e1b1336c80707349b8ce27

        <!-- Temple Entrance -->
        <a-entity gltf-model="#temple_entrance" position="-30 10 5" scale="0.05 0.05 0.05" rotation="0 0 0"></a-entity>

        <!-- Roman Temple -->
        <a-entity gltf-model="#roman_temple_main" position="1.000 0 -45.007" scale="0.0085 0.0085 0.0085"
            rotation="0 360 0"></a-entity>

        <!-- Oasis Trading Post -->
        <a-entity gltf-model="#oasis_trading_post" position="-45.058 6.540 -36.086" scale="8.947 8.947 8.947"
            rotation="0 134.529 0"></a-entity>

        <!-- Aztec Style Temple Kit -->
        <a-entity gltf-model="#aztec_style_temple_kit" position="26.793 1.627 54.707" scale="6.122 6.122 6.122"
            rotation="0 45 0"></a-entity>

        <!-- Modèle GLB (aligné au groupe) -->
        <a-entity gltf-model="#piramid" position="0 1 0" scale="4 4 4" rotation="0 0 0"></a-entity>
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
            <a-asset-item id="pyramid" src="../assets/modelAvatar/Pyramid.glb"></a-asset-item>
            <a-asset-item id="sarcophagus" src="../assets/modelAvatar/stone_sarcophagi_cairo_museum.glb"></a-asset-item>
            <a-asset-item id="roman_temple" src="../assets/modelAvatar/low_poly_roman_temple_wip.glb"></a-asset-item>
            <a-asset-item id="piramid" src="../assets/modelAvatar/piramid.glb"></a-asset-item>
            <a-asset-item id="chest_glb" src="../assets/modelAvatar/chest.glb"></a-asset-item>
            <a-asset-item id="tent" src="../assets/modelAvatar/Tent.glb"></a-asset-item>
            <a-asset-item id="camels_respite" src="../assets/modelAvatar/camels_respite.glb"></a-asset-item>
            <a-asset-item id="temple_entrance" src="../assets/modelAvatar/temple_entrance.glb"></a-asset-item>
            <a-asset-item id="roman_temple_main" src="../assets/modelAvatar/roman_temple.glb"></a-asset-item>
            <a-asset-item id="oasis_trading_post" src="../assets/modelAvatar/oasis_trading_post.glb"></a-asset-item>
<<<<<<< HEAD
            <a-asset-item id="aztec_style_temple_kit"
                src="../assets/modelAvatar/aztec_style_temple_kit.glb"></a-asset-item>
=======
            <a-asset-item id="aztec_style_temple_kit" src="../assets/modelAvatar/aztec_style_temple_kit.glb"></a-asset-item>
>>>>>>> 25674d7752907d8e03e1b1336c80707349b8ce27
            <?php if ($modelAvatar): ?>
                <a-asset-item id="avatar" src="../<?php echo htmlspecialchars($modelAvatar); ?>"></a-asset-item>
            <?php endif; ?>
        </a-assets>
    </a-scene>
</body>

</html>