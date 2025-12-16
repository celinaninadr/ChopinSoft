<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chopin VR</title>
  <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <header class="topbar">
    <a class="brand" href="/ChopinSoft/index.php?route=home/index">Chopin VR</a>
    <nav class="nav">
      <a href="/ChopinSoft/index.php?route=home/index">Accueil</a>
      <?php if (!empty($_SESSION['user'])): ?>
        <?php if (($_SESSION['user']['userRole'] ?? '') === 'ADMIN'): ?>
          <a href="/ChopinSoft/index.php?route=admin/users">Admin</a>
        <?php else: ?>
          <a href="/ChopinSoft/index.php?route=user/profile">Profil</a>
        <?php endif; ?>
        <a href="/ChopinSoft/index.php?route=user/logout">Déconnexion</a>
      <?php endif; ?>
    </nav>
  </header>

  <?php $flash = getFlash(); if ($flash): ?>
    <div class="flash"><?php echo htmlspecialchars($flash); ?></div>
  <?php endif; ?>

  <main class="container">
    <?php echo $content; ?>
  </main>
</body>
</html>
