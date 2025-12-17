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
        <!-- <a-scene renderer="physicallyCorrectLights: true;"> -->
        <a-entity id="rig" rotation="0 0 0">
            <a-entity camera position="-10 3.8 -9" wasd-controls look-controls></a-entity>
            <?php if ($modelAvatar): ?>
            <a-entity gltf-model="#avatar" position="0 0 0" scale="1 1 1" rotation="0 0 0"></a-entity>
            <?php endif; ?>
        </a-entity>
        <a-entity environment="preset: egypt; groundYScale: 6; fog: 0.7"></a-entity>
        <a-entity light="type:ambient;intensity:1.0"></a-entity>
        <a-entity gltf-model="#sphynx" position="20 5 20" scale="10 10 10" rotation="0 30 0"> </a-entity>
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
        <a-entity gltf-model="#sarcophagus" position="-10 0 0" scale="2 2 2" rotation="0 0 0"> </a-entity>
        <a-assets>
            <a-asset-item id="sphynx" src="./assets/sphynx.glb"></a-asset-item>
            <a-asset-item id="chest" src="./assets/free_animated_low_poly_cartoon_chest_kit.glb"></a-asset-item>
            <a-asset-item id="camel" src="./assets/low_poly_western_camel_camelops_hesternus.glb"></a-asset-item>
            <a-asset-item id="anubis" src="./assets/Anubis Statue.glb"></a-asset-item>
            <a-asset-item id="arch" src="./assets/Arch.glb"></a-asset-item>
            <a-asset-item id="fence" src="./assets/Fence Pillar.glb"></a-asset-item>
            <a-asset-item id="coin" src="./assets/lowpoly_gold_coin.glb"></a-asset-item>
            <a-asset-item id="pyramid" src="./assets/Pyramid.glb"></a-asset-item>
            <a-asset-item id="sarcophagus" src="./assets/stone_sarcophagi_cairo_museum.glb"></a-asset-item>
            <?php if ($modelAvatar): ?>
            <a-asset-item id="avatar" src="<?php echo htmlspecialchars($modelAvatar); ?>"></a-asset-item>
            <?php endif; ?>
        </a-assets>
</body>

</html></content>
<parameter name="filePath">c:\xampp\project\ChopinSoft\desert.php