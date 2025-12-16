<h1>CRUD Avatars</h1>

<div class="actions">
  <a class="btn" href="/ChopinSoft/index.php?route=admin/users">Retour</a>
  <a class="btn" href="/ChopinSoft/index.php?route=admin/worlds">CRUD mondes</a>
</div>

<h2>Ajouter</h2>
<form class="card" method="post">
  <input type="hidden" name="action" value="create">
  <div class="grid2">
    <div>
      <label>Nom</label>
      <input name="nameAvatar" required>
    </div>
    <div>
      <label>Fichier 3D (URL .glb)</label>
      <input name="modelAvatar" required>
    </div>
  </div>
  <label>Image (URL optionnelle)</label>
  <input name="imgAvatar">
  <div class="actions">
    <button class="btn" type="submit">Ajouter</button>
  </div>
</form>

<h2>Liste</h2>
<?php foreach ($avatars as $a): ?>
  <div class="card">
    <form method="post">
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="idAvatar" value="<?php echo (int)$a['idAvatar']; ?>">
      <div class="grid2">
        <div>
          <label>Nom</label>
          <input name="nameAvatar" value="<?php echo htmlspecialchars($a['nameAvatar']); ?>" required>
        </div>
        <div>
          <label>Fichier 3D (URL .glb)</label>
          <input name="modelAvatar" value="<?php echo htmlspecialchars($a['modelAvatar']); ?>" required>
        </div>
      </div>
      <label>Image</label>
      <input name="imgAvatar" value="<?php echo htmlspecialchars($a['imgAvatar'] ?? ''); ?>">
      <div class="actions">
        <button class="btn" type="submit">Modifier</button>
      </div>
    </form>

    <form method="post" onsubmit="return confirm('Supprimer ?')">
      <input type="hidden" name="action" value="delete">
      <input type="hidden" name="idAvatar" value="<?php echo (int)$a['idAvatar']; ?>">
      <button class="btn danger" type="submit">Supprimer</button>
    </form>

    <div class="card">
      <p class="small">Prévisualisation 3D:</p>
      <model-viewer src="<?php echo htmlspecialchars($a['modelAvatar']); ?>" camera-controls auto-rotate></model-viewer>
    </div>
  </div>
<?php endforeach; ?>
