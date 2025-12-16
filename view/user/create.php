<h1>Créer ton avatar</h1>

<form class="card" method="post">
  <div class="grid2">
    <div>
      <label>Username</label>
      <input name="username" required>
    </div>
    <div>
      <label>Password</label>
      <input type="password" name="password" required>
    </div>
  </div>

  <h2>Choisir un monde</h2>
  <input type="hidden" name="idWorld" id="idWorld" value="">
  <div class="worlds">
    <?php foreach ($worlds as $w): ?>
      <div class="world" onclick="document.getElementById('idWorld').value='<?php echo (int)$w['idWorld']; ?>';">
        <?php echo htmlspecialchars($w['nameWorld']); ?>
      </div>
    <?php endforeach; ?>
  </div>
  <p class="small">Clique sur un monde avant de jouer.</p>

  <h2>Choisir un avatar (3D)</h2>
  <div class="avatars">
    <?php foreach ($avatars as $a): ?>
      <label class="avatar">
        <input type="radio" name="idAvatar" value="<?php echo (int)$a['idAvatar']; ?>" required>
        <div><b><?php echo htmlspecialchars($a['nameAvatar']); ?></b></div>
        <model-viewer src="<?php echo htmlspecialchars($a['modelAvatar']); ?>" camera-controls auto-rotate></model-viewer>
      </label>
    <?php endforeach; ?>
  </div>

  <div class="actions">
    <button class="btn" type="submit">Jouer</button>
  </div>
</form>
