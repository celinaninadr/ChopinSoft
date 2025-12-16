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
  <div class="carousel-container">
    <button type="button" class="carousel-btn prev" id="prevBtn">‹</button>
    
    <div class="carousel-wrapper">
      <div class="avatars-carousel" id="avatarsCarousel">
        <?php foreach ($avatars as $a): ?>
          <label class="avatar-slide" data-avatar-id="<?php echo (int)$a['idAvatar']; ?>">
            <input type="radio" name="idAvatar" value="<?php echo (int)$a['idAvatar']; ?>" required>
            <div class="avatar-name"><b><?php echo htmlspecialchars($a['nameAvatar']); ?></b></div>
            <model-viewer 
              src="<?php echo htmlspecialchars($a['modelAvatar']); ?>" 
              camera-controls 
              auto-rotate
              shadow-intensity="1"
              exposure="1.0"
              camera-orbit="0deg 75deg 8m"
              min-camera-orbit="auto auto 2m"
              max-camera-orbit="auto auto 12m"
              loading="eager"
            ></model-viewer>
          </label>
        <?php endforeach; ?>
      </div>
    </div>
    
    <button type="button" class="carousel-btn next" id="nextBtn">›</button>
  </div>
  
  <div class="carousel-indicators" id="carouselIndicators"></div>
  <div class="carousel-counter" id="carouselCounter"></div>

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

  // Gestion du carousel d'avatars
  const carousel = document.getElementById('avatarsCarousel');
  const slides = document.querySelectorAll('.avatar-slide');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  const indicatorsContainer = document.getElementById('carouselIndicators');
  const counterElement = document.getElementById('carouselCounter');
  
  let currentIndex = 0;
  const totalSlides = slides.length;

  // Créer les indicateurs
  function createIndicators() {
    indicatorsContainer.innerHTML = '';
    for (let i = 0; i < totalSlides; i++) {
      const indicator = document.createElement('div');
      indicator.classList.add('indicator');
      if (i === 0) indicator.classList.add('active');
      indicator.addEventListener('click', () => goToSlide(i));
      indicatorsContainer.appendChild(indicator);
    }
  }

  // Mettre à jour le compteur
  function updateCounter() {
    counterElement.textContent = `Avatar ${currentIndex + 1} sur ${totalSlides}`;
  }

  // Aller à un slide spécifique
  function goToSlide(index) {
    if (index < 0) index = 0;
    if (index >= totalSlides) index = totalSlides - 1;
    
    currentIndex = index;
    carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
    
    // Mettre à jour les classes active
    slides.forEach((slide, i) => {
      slide.classList.toggle('active', i === currentIndex);
    });
    
    // Mettre à jour les indicateurs
    document.querySelectorAll('.indicator').forEach((ind, i) => {
      ind.classList.toggle('active', i === currentIndex);
    });
    
    // Sélectionner automatiquement le radio button
    const currentSlide = slides[currentIndex];
    const radio = currentSlide.querySelector('input[type="radio"]');
    if (radio) {
      radio.checked = true;
    }
    
    // Mettre à jour les boutons
    prevBtn.disabled = currentIndex === 0;
    nextBtn.disabled = currentIndex === totalSlides - 1;
    
    // Mettre à jour le compteur
    updateCounter();
  }

  // Navigation
  prevBtn.addEventListener('click', () => goToSlide(currentIndex - 1));
  nextBtn.addEventListener('click', () => goToSlide(currentIndex + 1));

  // Navigation au clavier
  document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') {
      prevBtn.click();
    } else if (e.key === 'ArrowRight') {
      nextBtn.click();
    }
  });

  // Clic sur un slide pour le sélectionner
  slides.forEach((slide, index) => {
    slide.addEventListener('click', () => {
      if (index !== currentIndex) {
        goToSlide(index);
      }
    });
  });

  // Support du swipe sur mobile
  let touchStartX = 0;
  let touchEndX = 0;

  carousel.addEventListener('touchstart', (e) => {
    touchStartX = e.changedTouches[0].screenX;
  });

  carousel.addEventListener('touchend', (e) => {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
  });

  function handleSwipe() {
    if (touchStartX - touchEndX > 50) {
      // Swipe left
      nextBtn.click();
    }
    if (touchEndX - touchStartX > 50) {
      // Swipe right
      prevBtn.click();
    }
  }

  // Initialisation
  createIndicators();
  goToSlide(0);

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