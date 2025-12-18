<!DOCTYPE html>
<html class="dark" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Sélection du Profil (Night City)</title>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&amp;family=Spline+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.3.0/model-viewer.min.js"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#00f3ff",
                        "accent": "#d946ef",
                        "background-light": "#0b0b15",
                        "background-dark": "#05050a",
                        "surface-light": "#161b2e",
                        "surface-dark": "#10101a",
                        "text-main": "#ffffff",
                        "text-sub": "#94a3b8",
                        "cyber-purple": "#9d00ff",
                        "cyber-blue": "#0066ff",
                        "cyber-pink": "#ff00cc"
                    },
                    fontFamily: {
                        "display": ["Rajdhani", "sans-serif"],
                        "body": ["Spline Sans", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "0.3rem", "lg": "0.5rem", "xl": "1rem", "full": "9999px"},
                    boxShadow: {
                        "neon-primary": "0 0 15px rgba(0, 243, 255, 0.7), 0 0 30px rgba(0, 243, 255, 0.3), inset 0 0 10px rgba(0, 243, 255, 0.2)",
                        "neon-accent": "0 0 15px rgba(217, 70, 239, 0.7), 0 0 30px rgba(217, 70, 239, 0.3), inset 0 0 10px rgba(217, 70, 239, 0.2)",
                        "neon-purple": "0 0 15px rgba(157, 0, 255, 0.7), 0 0 30px rgba(157, 0, 255, 0.3)",
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'glitch': 'glitch 0.5s infinite',
                        'scanline': 'scanline 10s linear infinite',
                        'fade-in': 'fadeIn 0.8s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'neon-pulse': 'neonPulse 2s infinite'
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        glitch: {
                            '0%, 100%': { transform: 'translate(0)' },
                            '20%': { transform: 'translate(-2px, 2px)' },
                            '40%': { transform: 'translate(-2px, -2px)' },
                            '60%': { transform: 'translate(2px, 2px)' },
                            '80%': { transform: 'translate(2px, -2px)' }
                        },
                        scanline: {
                            '0%': { transform: 'translateY(-100%)' },
                            '100%': { transform: 'translateY(100vh)' }
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        neonPulse: {
                            '0%, 100%': { opacity: '1' },
                            '50%': { opacity: '0.7' }
                        }
                    }
                },
            },
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .text-glow {
                text-shadow: 0 0 10px rgba(0, 243, 255, 0.5);
            }
            .text-glow-accent {
                text-shadow: 0 0 10px rgba(217, 70, 239, 0.5);
            }
            .text-stroke {
                -webkit-text-stroke: 1px rgba(0, 243, 255, 0.3);
            }
            .cyber-grid {
                background-image: 
                    linear-gradient(rgba(0, 243, 255, 0.05) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(0, 243, 255, 0.05) 1px, transparent 1px);
                background-size: 50px 50px;
            }
            .gradient-border {
                border: 2px solid transparent;
                background: linear-gradient(135deg, #161b2e, #10101a) padding-box,
                            linear-gradient(135deg, #00f3ff, #d946ef) border-box;
            }
        }
    </style>
    <style>
        /* Scanline effect */
        .scanlines {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                to bottom,
                transparent 50%,
                rgba(0, 243, 255, 0.03) 50%
            );
            background-size: 100% 4px;
            pointer-events: none;
            z-index: 10;
            opacity: 0.6;
        }
        
        .cyber-glitch {
            position: relative;
        }
        
        .cyber-glitch::before,
        .cyber-glitch::after {
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.8;
        }
        
        .cyber-glitch::before {
            color: #ff00cc;
            z-index: -1;
            animation: glitch 0.3s infinite;
        }
        
        .cyber-glitch::after {
            color: #00f3ff;
            z-index: -2;
            animation: glitch 0.5s infinite reverse;
        }
        
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #00f3ff, #d946ef);
            border-radius: 10px;
        }
        
        /* Carousel styles améliorés */
        .carousel-container {
            position: relative;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .carousel-wrapper {
            flex: 1;
            overflow: hidden;
            border-radius: 0.5rem;
        }

        .worlds-carousel, .avatars-carousel {
            display: flex;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .world-slide, .avatar-slide {
            min-width: 100%;
            padding: 1rem;
            cursor: pointer;
            opacity: 0.5;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .world-slide.active, .avatar-slide.active {
            opacity: 1;
            transform: scale(1.02);
        }

        .world-slide::before, .avatar-slide::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 0.5rem;
            background: linear-gradient(135deg, rgba(0, 243, 255, 0.1), rgba(217, 70, 239, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .world-slide.active::before, .avatar-slide.active::before {
            opacity: 1;
        }

        .world-slide input[type="radio"], .avatar-slide input[type="radio"] {
            display: none;
        }

        .world-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            position: relative;
        }

        .world-slide.active .world-image {
            border-color: #00f3ff;
            box-shadow: 0 0 30px rgba(0, 243, 255, 0.5);
            animation: neonPulse 2s infinite;
        }

        .world-placeholder {
            width: 100%;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #161b2e, #10101a);
            border-radius: 0.5rem;
            border: 2px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .world-placeholder::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent 30%,
                rgba(0, 243, 255, 0.1) 50%,
                transparent 70%
            );
            animation: rotate 4s linear infinite;
        }

        .world-placeholder span {
            position: relative;
            z-index: 2;
            background: rgba(16, 16, 26, 0.8);
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .carousel-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(0, 243, 255, 0.1);
            border: 1px solid rgba(0, 243, 255, 0.3);
            color: #00f3ff;
            font-size: 1.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .carousel-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 243, 255, 0.4), transparent);
            transition: left 0.5s;
        }

        .carousel-btn:hover::before {
            left: 100%;
        }

        .carousel-btn:hover:not(:disabled) {
            background: rgba(0, 243, 255, 0.2);
            box-shadow: 0 0 20px rgba(0, 243, 255, 0.7);
            transform: scale(1.1);
        }

        .carousel-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .carousel-indicators {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.75rem;
        }

        .indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .indicator::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(0, 243, 255, 0.5);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .indicator.active {
            background: transparent;
            border-color: #00f3ff;
            box-shadow: 0 0 10px rgba(0, 243, 255, 0.5);
        }

        .indicator.active::before {
            opacity: 1;
        }

        .carousel-counter {
            text-align: center;
            margin-top: 0.25rem;
            font-family: 'Rajdhani', sans-serif;
            color: #94a3b8;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            position: relative;
            padding: 0.25rem 0.5rem;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 0.25rem;
            display: inline-block;
            margin: 0.5rem auto;
        }

        model-viewer {
            width: 100%;
            height: 300px;
            border-radius: 0.5rem;
            background: linear-gradient(135deg, #161b2e, #10101a);
            border: 2px solid transparent;
            transition: all 0.3s ease;
            position: relative;
        }

        .avatar-slide.active model-viewer {
            border-color: #d946ef;
            box-shadow: 0 0 30px rgba(217, 70, 239, 0.5);
            animation: neonPulse 2s infinite alternate;
        }

        model-viewer::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 60%, rgba(0, 243, 255, 0.1));
            pointer-events: none;
            border-radius: 0.5rem;
        }

        .world-name, .avatar-name {
            text-align: center;
            margin-bottom: 0.5rem;
            font-family: 'Rajdhani', sans-serif;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #fff;
            position: relative;
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background: rgba(0, 0, 0, 0.4);
            border-radius: 0.25rem;
        }

        .world-name::after, .avatar-name::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 10%;
            right: 10%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #00f3ff, transparent);
            opacity: 0.5;
        }
        
        /* Progress bar for loading */
        .loading-bar {
            height: 3px;
            background: linear-gradient(90deg, #00f3ff, #d946ef);
            width: 0%;
            position: absolute;
            top: 0;
            left: 0;
            transition: width 0.3s ease;
        }
        
        /* Section headers */
        .section-header {
            position: relative;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .section-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #00f3ff, transparent);
        }
    </style>
</head>
<body class="bg-background-dark text-text-main font-body overflow-x-hidden antialiased selection:bg-accent selection:text-white cyber-grid">
<div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
    <div class="absolute top-[-10%] left-[20%] w-[500px] h-[500px] bg-purple-900/20 rounded-full blur-[128px] animate-pulse-slow"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-blue-900/10 rounded-full blur-[128px] animate-pulse-slow" style="animation-delay: 1s"></div>
    <div class="scanlines"></div>
</div>
<div class="relative flex min-h-screen w-full flex-col group/design-root z-10">
    <main class="layout-container flex h-full grow flex-col items-center pt-6 pb-12">
        <div class="layout-content-container flex flex-col max-w-[960px] w-full flex-1 px-4 sm:px-6 lg:px-8">
            <form method="post" id="avatarForm" class="animate-fade-in">
                <div class="flex flex-col gap-4 mb-8 animate-slide-up" style="animation-delay: 0.2s">
                    <div class="flex flex-wrap justify-between gap-3 items-end">
                        <div class="flex flex-col gap-2">
                            <h1 class="text-3xl md:text-5xl font-display font-black leading-none tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-white via-primary to-accent drop-shadow-[0_0_15px_rgba(0,243,255,0.5)] cyber-glitch" data-text="SÉLECTION DU PROFIL">
                                SÉLECTION DU PROFIL
                            </h1>
                            <p class="text-primary text-base font-display font-medium tracking-wide uppercase text-glow animate-pulse">
                                <span class="material-symbols-outlined align-middle mr-1">security</span>
                                Initialisation du système // Protocole 0.9
                            </p>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-2 bg-surface-dark/80 rounded-lg border border-white/10">
                            <span class="text-accent text-sm font-display font-bold">STATUS:</span>
                            <div class="flex items-center gap-1">
                                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                                <span class="text-xs font-display">EN LIGNE</span>
                            </div>
                        </div>
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

    function updateCounter() {
        counterElement.textContent = `AVATAR ${currentIndex + 1}/${totalSlides}`;
        updatePreparationBar();
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

    slides.forEach((slide, index) => {
        slide.addEventListener('click', () => {
            if (index !== currentIndex) {
                goToSlide(index);
            }
        });
    });

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

    // Update preparation bar on input changes
    document.getElementById('username').addEventListener('input', updatePreparationBar);
    document.getElementById('password').addEventListener('input', updatePreparationBar);
    
    // Add hover effects to all inputs
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('ring-2', 'ring-primary/30');
        });
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('ring-2', 'ring-primary/30');
        });
    });

    document.getElementById('avatarForm').addEventListener('submit', function (e) {
        if (!idWorldInput.value) {
            e.preventDefault();
            // Create custom alert
            const alertDiv = document.createElement('div');
            alertDiv.className = 'fixed top-4 right-4 bg-red-900/80 border border-red-500 text-white p-4 rounded-lg shadow-neon-accent z-50 animate-slide-up';
            alertDiv.innerHTML = `
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-red-400">warning</span>
                    <div>
                        <p class="font-display font-bold">ERREUR DE SÉLECTION</p>
                        <p class="text-sm mt-1">Tu dois sélectionner un secteur avant de jouer !</p>
                    </div>
                </div>
            `;
            document.body.appendChild(alertDiv);
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
            
            // Highlight world carousel
            const worldSection = document.querySelector('section:nth-child(3)');
            worldSection.style.animation = 'none';
            setTimeout(() => {
                worldSection.style.animation = 'glitch 0.3s';
                setTimeout(() => {
                    worldSection.style.animation = '';
                }, 300);
            }, 10);
            
            return false;
        }
        
        // Add loading effect
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span><span>INITIALISATION...</span>';
        submitBtn.disabled = true;
        
        // Simulate loading for better UX
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 1500);
    });

    // Add ripple effect to buttons
    document.querySelectorAll('.carousel-btn, button[type="submit"], a').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.3);
                transform: scale(0);
                animation: ripple 0.6s linear;
                width: ${size}px;
                height: ${size}px;
                top: ${y}px;
                left: ${x}px;
                pointer-events: none;
            `;
            
            this.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        });
    });

    // Add CSS for ripple animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
</script>
</body>
</html>