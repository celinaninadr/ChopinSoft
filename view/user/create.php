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
  <div class="carousel-container">
    <button type="button" class="carousel-btn prev" id="prevWorldBtn">‹</button>
    
    <div class="carousel-wrapper">
      <div class="worlds-carousel" id="worldsCarousel">
        <?php foreach ($worlds as $w): ?>
          <label class="world-slide" data-world-id="<?php echo (int)$w['idWorld']; ?>">
            <div class="world-name"><b><?php echo htmlspecialchars($w['nameWorld']); ?></b></div>
            <?php if (isset($w['imgWorld']) && !empty($w['imgWorld'])): ?>
              <img src="<?php echo htmlspecialchars($w['imgWorld']); ?>" alt="<?php echo htmlspecialchars($w['nameWorld']); ?>" class="world-image">
            <?php else: ?>
              <div class="world-placeholder">
                <span><?php echo htmlspecialchars($w['nameWorld']); ?></span>
              </div>
            <?php endif; ?>
          </label>
        <?php endforeach; ?>
      </div>
    </div>
    
    <button type="button" class="carousel-btn next" id="nextWorldBtn">›</button>
  </div>
  
  <div class="carousel-indicators" id="worldIndicators"></div>
  <div class="carousel-counter" id="worldCounter"></div>
  <input type="hidden" name="idWorld" id="idWorld" value="">

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
              camera-orbit="0deg 75deg 4m"
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
  // ===========================================
  // CAROUSEL DES MONDES
  // ===========================================
  const worldCarousel = document.getElementById('worldsCarousel');
  const worldSlides = document.querySelectorAll('.world-slide');
  const prevWorldBtn = document.getElementById('prevWorldBtn');
  const nextWorldBtn = document.getElementById('nextWorldBtn');
  const worldIndicatorsContainer = document.getElementById('worldIndicators');
  const worldCounterElement = document.getElementById('worldCounter');
  const idWorldInput = document.getElementById('idWorld');
  
  let currentWorldIndex = 0;
  const totalWorldSlides = worldSlides.length;

  // Créer les indicateurs pour les mondes
  function createWorldIndicators() {
    worldIndicatorsContainer.innerHTML = '';
    for (let i = 0; i < totalWorldSlides; i++) {
      const indicator = document.createElement('div');
      indicator.classList.add('indicator');
      if (i === 0) indicator.classList.add('active');
      indicator.addEventListener('click', () => goToWorldSlide(i));
      worldIndicatorsContainer.appendChild(indicator);
    }
  }

  // Mettre à jour le compteur des mondes
  function updateWorldCounter() {
    worldCounterElement.textContent = `Monde ${currentWorldIndex + 1} sur ${totalWorldSlides}`;
  }

  // Aller à un slide spécifique pour les mondes
  function goToWorldSlide(index) {
    if (index < 0) index = 0;
    if (index >= totalWorldSlides) index = totalWorldSlides - 1;
    
    currentWorldIndex = index;
    worldCarousel.style.transform = `translateX(-${currentWorldIndex * 100}%)`;
    
    // Mettre à jour les classes active
    worldSlides.forEach((slide, i) => {
      slide.classList.toggle('active', i === currentWorldIndex);
    });
    
    // Mettre à jour les indicateurs
    const worldIndicators = worldIndicatorsContainer.querySelectorAll('.indicator');
    worldIndicators.forEach((ind, i) => {
      ind.classList.toggle('active', i === currentWorldIndex);
    });
    
    // Mettre à jour l'input hidden avec l'ID du monde
    const currentWorldSlide = worldSlides[currentWorldIndex];
    idWorldInput.value = currentWorldSlide.getAttribute('data-world-id');
    
    // Mettre à jour les boutons
    prevWorldBtn.disabled = currentWorldIndex === 0;
    nextWorldBtn.disabled = currentWorldIndex === totalWorldSlides - 1;
    
    // Mettre à jour le compteur
    updateWorldCounter();
  }

  // Navigation pour les mondes
  prevWorldBtn.addEventListener('click', () => goToWorldSlide(currentWorldIndex - 1));
  nextWorldBtn.addEventListener('click', () => goToWorldSlide(currentWorldIndex + 1));

  // Clic sur un slide de monde pour le sélectionner
  worldSlides.forEach((slide, index) => {
    slide.addEventListener('click', () => {
      if (index !== currentWorldIndex) {
        goToWorldSlide(index);
      }
    });
  });

  // Support du swipe sur mobile pour les mondes
  let worldTouchStartX = 0;
  let worldTouchEndX = 0;

  worldCarousel.addEventListener('touchstart', (e) => {
    worldTouchStartX = e.changedTouches[0].screenX;
  });

  worldCarousel.addEventListener('touchend', (e) => {
    worldTouchEndX = e.changedTouches[0].screenX;
    handleWorldSwipe();
  });

  function handleWorldSwipe() {
    if (worldTouchStartX - worldTouchEndX > 50) {
      nextWorldBtn.click();
    }
    if (worldTouchEndX - worldTouchStartX > 50) {
      prevWorldBtn.click();
    }
  }

  // ===========================================
  // CAROUSEL DES AVATARS
  // ===========================================
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
    document.querySelectorAll('#carouselIndicators .indicator').forEach((ind, i) => {
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
      // Vérifier si on est sur le carousel des avatars ou des mondes
      // Pour simplifier, on navigue dans les deux
      if (document.activeElement.closest('.avatars-carousel')) {
        prevBtn.click();
      } else {
        prevWorldBtn.click();
      }
    } else if (e.key === 'ArrowRight') {
      if (document.activeElement.closest('.avatars-carousel')) {
        nextBtn.click();
      } else {
        nextWorldBtn.click();
      }
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
      nextBtn.click();
    }
    if (touchEndX - touchStartX > 50) {
      prevBtn.click();
    }
  }

  // ===========================================
  // INITIALISATION
  // ===========================================
  createWorldIndicators();
  goToWorldSlide(0);
  
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