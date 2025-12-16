<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Créer ton avatar</title>
  
  <!-- Importer model-viewer pour la 3D -->
  <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.3.0/model-viewer.min.js"></script>
  
</head>
<body>

<h1>Créer ton avatar</h1>

<form class="card" method="post" id="avatarForm">
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
  <div class="worlds" id="worldsContainer">
    <?php foreach ($worlds as $w): ?>
      <div class="world" data-world-id="<?php echo (int)$w['idWorld']; ?>">
        <?php echo htmlspecialchars($w['nameWorld']); ?>
      </div>
    <?php endforeach; ?>
  </div>
  <p class="small">Clique sur un monde avant de jouer.</p>

  <h2>Choisir un avatar (3D)</h2>
  <div class="avatars">
    <?php foreach ($avatars as $a): ?>
      <label class="avatar" data-avatar-id="<?php echo (int)$a['idAvatar']; ?>">
        <input type="radio" name="idAvatar" value="<?php echo (int)$a['idAvatar']; ?>" required>
        <div><b><?php echo htmlspecialchars($a['nameAvatar']); ?></b></div>
        <model-viewer 
          src="<?php echo htmlspecialchars($a['modelAvatar']); ?>" 
          camera-controls 
          auto-rotate
          shadow-intensity="1"
          exposure="1.0"
          camera-orbit="0deg 75deg 2.5m"
          min-camera-orbit="auto auto 1m"
          max-camera-orbit="auto auto 10m"
          loading="eager"
        ></model-viewer>
      </label>
    <?php endforeach; ?>
  </div>

  <div class="actions">
    <button class="btn" type="submit">Jouer</button>
  </div>
</form>

<script>
  // Gestion de la sélection des mondes
  const worlds = document.querySelectorAll('.world');
  const idWorldInput = document.getElementById('idWorld');
  
  worlds.forEach(world => {
    world.addEventListener('click', function() {
      // Retirer la classe selected de tous les mondes
      worlds.forEach(w => w.classList.remove('selected'));
      
      // Ajouter la classe selected au monde cliqué
      this.classList.add('selected');
      
      // Mettre à jour l'input hidden
      idWorldInput.value = this.getAttribute('data-world-id');
    });
  });

  // Gestion de la sélection des avatars
  const avatarLabels = document.querySelectorAll('.avatar');
  const avatarRadios = document.querySelectorAll('input[name="idAvatar"]');
  
  avatarRadios.forEach(radio => {
    radio.addEventListener('change', function() {
      // Retirer la classe selected de tous les avatars
      avatarLabels.forEach(label => label.classList.remove('selected'));
      
      // Ajouter la classe selected à l'avatar sélectionné
      if (this.checked) {
        this.closest('.avatar').classList.add('selected');
      }
    });
  });

  // Validation du formulaire
  document.getElementById('avatarForm').addEventListener('submit', function(e) {
    if (!idWorldInput.value) {
      e.preventDefault();
      alert('⚠️ Tu dois sélectionner un monde avant de jouer !');
      return false;
    }
  });

  // Animation au chargement
  window.addEventListener('load', () => {
    const card = document.querySelector('.card');
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    
    setTimeout(() => {
      card.style.transition = 'all 0.6s ease';
      card.style.opacity = '1';
      card.style.transform = 'translateY(0)';
    }, 100);
  });
</script>

</body>
</html>