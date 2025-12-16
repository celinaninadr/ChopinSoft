<h1>CRUD Mondes</h1>

<div class="actions">
  <a class="btn" href="/ChopinSoft/index.php?route=admin/users">Retour</a>
  <a class="btn" href="/ChopinSoft/index.php?route=admin/avatars">CRUD avatars</a>
</div>

<h2>Ajouter</h2>
<form class="card" method="post">
  <input type="hidden" name="action" value="create">
  <div class="grid2">
    <div>
      <label>Nom</label>
      <input name="nameWorld" required>
    </div>
    <div>
      <label>URL portail</label>
      <input name="urlWorld" required>
    </div>
  </div>
  <label>Image (URL)</label>
  <input name="imgWorld">
  <div class="actions">
    <button class="btn" type="submit">Ajouter</button>
  </div>
</form>

<h2>Liste</h2>
<?php foreach ($worlds as $w): ?>
  <div class="card">
    <?php if (!empty($w['imgWorld'])): ?>
      <div class="card">
        <p class="small">Prévisualisation :</p>
        <img src="<?php echo htmlspecialchars($w['imgWorld']); ?>" alt="world" style="max-width:320px;border-radius:10px;border:1px solid #eee;">
      </div>
    <?php endif; ?>
    <div class="actions">
      <a class="btn secondary" target="_blank" href="<?php echo htmlspecialchars($w['urlWorld']); ?>">Ouvrir le portail</a>
    </div>

    <form method="post">
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="idWorld" value="<?php echo (int)$w['idWorld']; ?>">
      <div class="grid2">
        <div>
          <label>Nom</label>
          <input name="nameWorld" value="<?php echo htmlspecialchars($w['nameWorld']); ?>" required>
        </div>
        <div>
          <label>URL</label>
          <input name="urlWorld" value="<?php echo htmlspecialchars($w['urlWorld']); ?>" required>
        </div>
      </div>
      <label>Image</label>
      <input name="imgWorld" value="<?php echo htmlspecialchars($w['imgWorld'] ?? ''); ?>">
      <div class="actions">
        <button class="btn" type="submit">Modifier</button>
      </div>
    </form>

    <form method="post" onsubmit="return confirm('Supprimer ?')">
      <input type="hidden" name="action" value="delete">
      <input type="hidden" name="idWorld" value="<?php echo (int)$w['idWorld']; ?>">
      <button class="btn danger" type="submit">Supprimer</button>
    </form>
  </div>
<?php endforeach; ?>
