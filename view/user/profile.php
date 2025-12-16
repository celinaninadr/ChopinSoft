<h1>Bonjour <?php echo htmlspecialchars($profile['username'] ?? ''); ?></h1>

<div class="card">
  <h2>Ton avatar</h2>
  <p><b><?php echo htmlspecialchars($profile['nameAvatar'] ?? ''); ?></b></p>
  <model-viewer src="<?php echo htmlspecialchars($profile['modelAvatar'] ?? ''); ?>" camera-controls auto-rotate></model-viewer>

  <h3>Changer d'avatar</h3>
  <form method="post" action="/ChopinSoft/index.php?route=user/changeAvatar">
    <select name="idAvatar" required>
      <?php foreach ($avatars as $a): ?>
        <option value="<?php echo (int)$a['idAvatar']; ?>" <?php echo ((int)$a['idAvatar'] === (int)$profile['idAvatar']) ? 'selected' : ''; ?>>
          <?php echo htmlspecialchars($a['nameAvatar']); ?>
        </option>
      <?php endforeach; ?>
    </select>
    <div class="actions">
      <button class="btn" type="submit">Changer</button>
    </div>
  </form>
</div>

<h2>Mondes</h2>
<form method="post" action="/ChopinSoft/index.php?route=user/changeWorld">
  <div class="worlds">
    <?php foreach ($worlds as $w): ?>
      <button class="world" type="submit" name="idWorld" value="<?php echo (int)$w['idWorld']; ?>">
        <?php echo htmlspecialchars($w['nameWorld']); ?>
      </button>
    <?php endforeach; ?>
  </div>
</form>

<div class="actions">
  <a class="btn" href="/ChopinSoft/index.php?route=user/play">Jouer</a>
</div>
