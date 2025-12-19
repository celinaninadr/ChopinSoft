<!doctype html>
<html class="dark" lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chopin VR</title>
  <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
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
            "text-sub": "#94a3b8"
          },
          fontFamily: {
            "display": ["Rajdhani", "sans-serif"],
          },
          boxShadow: {
            "neon-primary": "0 0 10px rgba(0, 243, 255, 0.5), 0 0 20px rgba(0, 243, 255, 0.3)",
            "neon-accent": "0 0 10px rgba(217, 70, 239, 0.5), 0 0 20px rgba(217, 70, 239, 0.3)",
          },
          animation: {
            'pulse-slow': 'pulse 3s infinite',
            'slide-down': 'slideDown 0.3s ease-out',
            'fade-in': 'fadeIn 0.5s ease-out',
            'glitch': 'glitch 0.5s infinite',
          },
          keyframes: {
            slideDown: {
              '0%': { transform: 'translateY(-10px)', opacity: '0' },
              '100%': { transform: 'translateY(0)', opacity: '1' }
            },
            fadeIn: {
              '0%': { opacity: '0' },
              '100%': { opacity: '1' }
            },
            glitch: {
              '0%, 100%': { transform: 'translate(0)' },
              '20%': { transform: 'translate(-1px, 1px)' },
              '40%': { transform: 'translate(-1px, -1px)' },
              '60%': { transform: 'translate(1px, 1px)' },
              '80%': { transform: 'translate(1px, -1px)' }
            }
          }
        },
      },
    }
  </script>
  <style>
    :root {
      --primary: #00f3ff;
      --accent: #d946ef;
      --background-dark: #05050a;
      --surface-dark: #10101a;
    }
    
    body {
      background-color: var(--background-dark);
      color: white;
      font-family: system-ui, -apple-system, sans-serif;
      margin: 0;
      min-height: 100vh;
    }
    
    /* Cyberpunk navbar */
    .cyber-navbar {
      background: linear-gradient(135deg, rgba(16, 16, 26, 0.95), rgba(11, 11, 21, 0.98));
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(0, 243, 255, 0.2);
      position: relative;
      z-index: 100;
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
    }
    
    .cyber-navbar::before {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--primary), transparent);
    }
    
    .nav-grid {
      background-image: 
        linear-gradient(rgba(0, 243, 255, 0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0, 243, 255, 0.03) 1px, transparent 1px);
      background-size: 20px 20px;
    }
    
    .brand-logo {
      position: relative;
      overflow: hidden;
    }
    
    .brand-logo::after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 0;
      width: 100%;
      height: 2px;
      background: linear-gradient(90deg, transparent, var(--primary), transparent);
      transform: scaleX(0);
      transition: transform 0.3s ease;
    }
    
    .brand-logo:hover::after {
      transform: scaleX(1);
    }
    
    .nav-link {
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
    }
    
    .nav-link::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(0, 243, 255, 0.1), transparent);
      transition: left 0.5s;
    }
    
    .nav-link:hover::before {
      left: 100%;
    }
    
    .nav-link.active {
      color: var(--primary);
      text-shadow: 0 0 10px rgba(0, 243, 255, 0.5);
    }
    
    .status-indicator {
      position: relative;
    }
    
    .status-indicator::after {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: #10b981;
      animation: pulse 2s infinite;
    }
    
    .user-badge {
      background: rgba(0, 0, 0, 0.3);
      border: 1px solid rgba(0, 243, 255, 0.2);
      transition: all 0.3s ease;
    }
    
    .user-badge:hover {
      border-color: var(--primary);
      box-shadow: 0 0 15px rgba(0, 243, 255, 0.3);
    }
    
    /* Flash messages cyberpunk */
    .cyber-flash {
      background: linear-gradient(135deg, rgba(22, 27, 46, 0.95), rgba(16, 16, 26, 0.98));
      border: 1px solid rgba(0, 243, 255, 0.3);
      border-left: 4px solid var(--primary);
      backdrop-filter: blur(10px);
      animation: slideDown 0.5s ease-out;
      box-shadow: 0 4px 20px rgba(0, 243, 255, 0.1);
    }
    
    .cyber-flash::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--primary), transparent);
    }
    
    /* Mobile menu */
    .mobile-menu-btn {
      transition: all 0.3s ease;
    }
    
    .mobile-menu-btn:hover {
      transform: scale(1.1);
      box-shadow: 0 0 15px rgba(0, 243, 255, 0.3);
    }
    
    .mobile-menu {
      background: linear-gradient(135deg, rgba(22, 27, 46, 0.98), rgba(16, 16, 26, 0.99));
      backdrop-filter: blur(15px);
      border-bottom: 1px solid rgba(0, 243, 255, 0.2);
      animation: slideDown 0.3s ease-out;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }
    
    /* Ripple effect */
    .ripple {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.2);
      transform: scale(0);
      animation: ripple 0.6s linear;
      pointer-events: none;
    }
    
    @keyframes ripple {
      to {
        transform: scale(4);
        opacity: 0;
      }
    }
    
    /* Cyberpunk scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: rgba(0, 0, 0, 0.2);
    }
    
    ::-webkit-scrollbar-thumb {
      background: linear-gradient(180deg, var(--primary), var(--accent));
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: linear-gradient(180deg, var(--accent), var(--primary));
    }
  </style>
</head>
<body class="bg-background-dark text-text-main nav-grid">
  <!-- Cyberpunk Navbar -->
  <header class="cyber-navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <!-- Logo -->
        <div class="flex items-center">
          <a href="/ChopinSoft/index.php?route=home/index" class="brand-logo flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-primary/20 to-accent/20 border border-primary/30">
              <span class="material-symbols-outlined text-primary">vrpano</span>
            </div>
            <div>
              <span class="text-2xl font-display font-bold tracking-tighter bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">
                CHOPIN VR
              </span>
              <div class="flex items-center gap-1">
                <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                <span class="text-xs text-text-sub font-display">SYSTEM ONLINE</span>
              </div>
            </div>
          </a>
        </div>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center space-x-1">
          <!-- Home link -->
          <a href="/ChopinSoft/index.php?route=home/index" 
             class="nav-link px-4 py-2 rounded-lg font-display font-medium text-sm tracking-wider hover:text-primary transition-all">
            <span class="material-symbols-outlined align-text-bottom mr-2">home</span>
            ACCUEIL
          </a>
          
          <?php if (!empty($_SESSION['user'])): ?>
            <!-- User info -->
            <div class="flex items-center gap-4 ml-4">
              <!-- Status indicator -->
              <div class="status-indicator px-3 py-1 rounded-full user-badge">
                <div class="flex items-center gap-2">
                  <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                  <span class="text-xs font-display text-primary">
                    <?php echo htmlspecialchars($_SESSION['user']['username'] ?? 'User'); ?>
                  </span>
                </div>
              </div>
              
              <?php if (($_SESSION['user']['userRole'] ?? '') === 'ADMIN'): ?>
                <!-- Admin link -->
                <a href="/ChopinSoft/index.php?route=admin/users" 
                   class="nav-link group relative px-4 py-2 rounded-lg font-display font-bold text-sm tracking-wider bg-gradient-to-r from-danger/20 to-warning/20 border border-danger/30 hover:border-danger/50 hover:shadow-[0_0_15px_rgba(239,68,68,0.3)] transition-all">
                  <span class="material-symbols-outlined align-text-bottom mr-2">admin_panel_settings</span>
                  ADMIN
                  <span class="absolute -top-1 -right-1 w-2 h-2 bg-danger rounded-full animate-pulse"></span>
                </a>
              <?php else: ?>
                <!-- Profile link -->
                <a href="/ChopinSoft/index.php?route=user/profile" 
                   class="nav-link px-4 py-2 rounded-lg font-display font-medium text-sm tracking-wider hover:text-primary hover:bg-white/5 transition-all">
                  <span class="material-symbols-outlined align-text-bottom mr-2">person</span>
                  PROFIL
                </a>
              <?php endif; ?>
              
              <!-- Logout link -->
              <a href="/ChopinSoft/index.php?route=user/logout" 
                 class="nav-link px-4 py-2 rounded-lg font-display font-medium text-sm tracking-wider hover:text-primary hover:bg-white/5 transition-all">
                <span class="material-symbols-outlined align-text-bottom mr-2">logout</span>
                DÉCONNEXION
              </a>
            </div>
          <?php else: ?>
            <!-- Visitor links -->
            <div class="flex items-center gap-2 ml-4">
              <a href="/ChopinSoft/index.php?route=admin/login" 
                 class="nav-link px-4 py-2 rounded-lg font-display font-bold text-sm tracking-wider bg-gradient-to-r from-primary/20 to-accent/20 border border-primary/30 hover:border-primary/50 hover:shadow-neon-primary transition-all">
                <span class="material-symbols-outlined align-text-bottom mr-2">person_add</span>
               ADMIN
              </a>
              
              <a href="/ChopinSoft/index.php?route=user/login" 
                 class="nav-link px-4 py-2 rounded-lg font-display font-medium text-sm tracking-wider hover:text-primary hover:bg-white/5 transition-all">
                <span class="material-symbols-outlined align-text-bottom mr-2">login</span>
                CONNEXION
              </a>
            </div>
          <?php endif; ?>
        </nav>

        <!-- Mobile menu button -->
        <div class="md:hidden">
          <button type="button" 
                  class="mobile-menu-btn inline-flex items-center justify-center p-2 rounded-md text-text-sub hover:text-white hover:bg-white/5 focus:outline-none"
                  onclick="toggleMobileMenu()">
            <span class="material-symbols-outlined">menu</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobileMenu" class="mobile-menu hidden md:hidden px-4 pb-4">
      <div class="space-y-2">
        <a href="/ChopinSoft/index.php?route=home/index" 
           class="nav-link block px-4 py-3 rounded-lg font-display font-medium hover:text-primary hover:bg-white/5 transition-all">
          <span class="material-symbols-outlined align-text-bottom mr-3">home</span>
          Accueil
        </a>
        
        <?php if (!empty($_SESSION['user'])): ?>
          <div class="px-4 py-3">
            <div class="flex items-center gap-3">
              <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
              <span class="text-sm font-display text-primary">
                Connecté en tant que: <?php echo htmlspecialchars($_SESSION['user']['username'] ?? 'User'); ?>
              </span>
            </div>
          </div>
          
          <?php if (($_SESSION['user']['userRole'] ?? '') === 'ADMIN'): ?>
            <a href="/ChopinSoft/index.php?route=admin/users" 
               class="nav-link block px-4 py-3 rounded-lg font-display font-bold bg-gradient-to-r from-danger/20 to-warning/20 border border-danger/30 hover:border-danger/50 transition-all">
              <span class="material-symbols-outlined align-text-bottom mr-3">admin_panel_settings</span>
              Administration
            </a>
          <?php else: ?>
            <a href="/ChopinSoft/index.php?route=user/profile" 
               class="nav-link block px-4 py-3 rounded-lg font-display font-medium hover:text-primary hover:bg-white/5 transition-all">
              <span class="material-symbols-outlined align-text-bottom mr-3">person</span>
              Profil
            </a>
          <?php endif; ?>
          
          <a href="/ChopinSoft/index.php?route=user/logout" 
             class="nav-link block px-4 py-3 rounded-lg font-display font-medium hover:text-primary hover:bg-white/5 transition-all">
            <span class="material-symbols-outlined align-text-bottom mr-3">logout</span>
            Déconnexion
          </a>
          
        <?php else: ?>
          <a href="/ChopinSoft/index.php?route=user/create" 
             class="nav-link block px-4 py-3 rounded-lg font-display font-bold bg-gradient-to-r from-primary/20 to-accent/20 border border-primary/30 hover:border-primary/50 transition-all">
            <span class="material-symbols-outlined align-text-bottom mr-3">person_add</span>
            Rejoindre Night City
          </a>
          
          <a href="/ChopinSoft/index.php?route=user/login" 
             class="nav-link block px-4 py-3 rounded-lg font-display font-medium hover:text-primary hover:bg-white/5 transition-all">
            <span class="material-symbols-outlined align-text-bottom mr-3">login</span>
            Se connecter
          </a>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <!-- Flash messages -->
  <?php if (!empty($_SESSION['flash'])): $flash = $_SESSION['flash']; unset($_SESSION['flash']); ?>
    <div class="cyber-flash max-w-4xl mx-auto mt-4 px-6 py-4 rounded-lg relative animate-fade-in">
      <div class="flex items-center gap-3">
        <span class="material-symbols-outlined text-primary">info</span>
        <div class="font-display">
          <div class="text-sm uppercase tracking-wider text-text-sub">SYSTEM NOTIFICATION</div>
          <div class="text-white"><?php echo htmlspecialchars($flash); ?></div>
        </div>
        <button onclick="this.parentElement.parentElement.remove()" 
                class="ml-auto text-text-sub hover:text-white transition-colors">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
    </div>
  <?php endif; ?>

  <main class="container mx-auto px-4 py-8">
    <?php echo $content; ?>
  </main>

  <script>
    // Toggle mobile menu
    function toggleMobileMenu() {
      const menu = document.getElementById('mobileMenu');
      menu.classList.toggle('hidden');
      menu.classList.toggle('animate-slide-down');
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
      const menu = document.getElementById('mobileMenu');
      const button = document.querySelector('.mobile-menu-btn');
      
      if (!menu.contains(event.target) && !button.contains(event.target) && !menu.classList.contains('hidden')) {
        menu.classList.add('hidden');
      }
    });

    // Ripple effect on buttons and links
    document.querySelectorAll('.nav-link, .mobile-menu-btn, .cyber-flash button').forEach(element => {
      element.addEventListener('click', function(e) {
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        const ripple = document.createElement('span');
        ripple.className = 'ripple';
        ripple.style.width = ripple.style.height = `${size}px`;
        ripple.style.left = `${x}px`;
        ripple.style.top = `${y}px`;
        
        this.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
      });
    });

    // Add active class to current page link
    document.addEventListener('DOMContentLoaded', function() {
      const currentPath = window.location.pathname + window.location.search;
      const links = document.querySelectorAll('.nav-link');
      
      links.forEach(link => {
        if (link.href.includes(currentPath)) {
          link.classList.add('active');
        }
      });
    });

    // Auto-remove flash messages after 5 seconds
    const flashMessage = document.querySelector('.cyber-flash');
    if (flashMessage) {
      setTimeout(() => {
        flashMessage.style.opacity = '0';
        flashMessage.style.transition = 'opacity 0.5s ease';
        setTimeout(() => flashMessage.remove(), 500);
      }, 5000);
    }

    // Add glitch effect to brand logo randomly
    setInterval(() => {
      const brandLogo = document.querySelector('.brand-logo');
      if (brandLogo && Math.random() > 0.8) {
        brandLogo.style.animation = 'none';
        setTimeout(() => {
          brandLogo.style.animation = 'glitch 0.3s';
          setTimeout(() => {
            brandLogo.style.animation = '';
          }, 300);
        }, 10);
      }
    }, 5000);
  </script>
</body>
</html>