<div class="center">
  <h1>Accueil</h1>
  <?php if (!empty($_SESSION['user'])): ?>
    <?php if (($_SESSION['user']['userRole'] ?? '') === 'ADMIN'): ?>
      <a class="btn" href="/ChopinSoft/index.php?route=admin/users">Administration</a>
    <?php else: ?>
      <a class="btn" href="/ChopinSoft/index.php?route=user/profile">Profil</a>
      <a class="btn" href="/ChopinSoft/index.php?route=user/play">Jouer</a>
    <?php endif; ?>
    <a class="btn secondary" href="/ChopinSoft/index.php?route=user/logout">Déconnexion</a>
  <?php else: ?>
    <a class="btn" href="/ChopinSoft/index.php?route=user/create">Créer ton avatar</a>
    <a class="btn secondary" href="/ChopinSoft/index.php?route=user/login">Se connecter</a>
    <a class="btn secondary" href="/ChopinSoft/index.php?route=admin/login">Login administrateur</a>
  <?php endif; ?>
</div>
