<!DOCTYPE html>
<html class="dark" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Profil - Night City</title>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js" type="module"></script>
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
                    },
                    fontFamily: {
                        "display": ["Rajdhani", "sans-serif"],
                    },
                    borderRadius: {"DEFAULT": "0.3rem", "lg": "0.5rem", "xl": "1rem", "full": "9999px"},
                    boxShadow: {
                        "neon-primary": "0 0 15px rgba(0, 243, 255, 0.7), 0 0 30px rgba(0, 243, 255, 0.3)",
                        "neon-accent": "0 0 15px rgba(217, 70, 239, 0.7), 0 0 30px rgba(217, 70, 239, 0.3)",
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s infinite',
                        'fade-in': 'fadeIn 0.8s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'glitch': 'glitch 0.5s infinite',
                        'scanline': 'scanline 10s linear infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        glitch: {
                            '0%, 100%': { transform: 'translate(0)' },
                            '20%': { transform: 'translate(-1px, 1px)' },
                            '40%': { transform: 'translate(-1px, -1px)' },
                            '60%': { transform: 'translate(1px, 1px)' },
                            '80%': { transform: 'translate(1px, -1px)' }
                        },
                        scanline: {
                            '0%': { transform: 'translateY(-100%)' },
                            '100%': { transform: 'translateY(100vh)' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' }
                        }
                    }
                },
            },
        }
    </script>
    <style>
        body {
            background: linear-gradient(135deg, #05050a, #0b0b15);
            color: white;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Cyberpunk background */
        .cyber-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(0, 243, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 243, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: 0;
        }
        
        /* Scanline effect */
        .scanlines {
            position: fixed;
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
            z-index: 1;
            opacity: 0.6;
            animation: scanline 10s linear infinite;
        }
        
        /* Glow effect */
        .glow-effect {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(0, 243, 255, 0.1) 0%, transparent 70%);
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }
        
        /* Main content */
        .main-content {
            position: relative;
            z-index: 10;
        }
        
        /* Cyber card */
        .cyber-card {
            background: linear-gradient(135deg, rgba(22, 27, 46, 0.9), rgba(16, 16, 26, 0.95));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .cyber-card:hover {
            border-color: rgba(0, 243, 255, 0.3);
            box-shadow: 0 0 30px rgba(0, 243, 255, 0.2);
        }
        
        /* Cyber select */
        .cyber-select {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 0.3rem;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            transition: all 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2300f3ff'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1rem;
        }
        
        .cyber-select:focus {
            outline: none;
            border-color: #00f3ff;
            box-shadow: 0 0 15px rgba(0, 243, 255, 0.5);
        }
        
        /* Cyber button */
        .cyber-btn {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, rgba(0, 243, 255, 0.2), rgba(217, 70, 239, 0.2));
            border: 1px solid rgba(0, 243, 255, 0.3);
            color: white;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .cyber-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.7s;
        }
        
        .cyber-btn:hover::before {
            left: 100%;
        }
        
        .cyber-btn:hover {
            box-shadow: 0 0 25px rgba(0, 243, 255, 0.5);
            transform: translateY(-2px);
        }
        
        /* World button */
        .world-btn {
            background: linear-gradient(135deg, rgba(22, 27, 46, 0.8), rgba(16, 16, 26, 0.9));
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .world-btn:hover {
            border-color: #00f3ff;
            box-shadow: 0 0 20px rgba(0, 243, 255, 0.3);
            transform: translateY(-5px) scale(1.05);
        }
        
        /* Status indicator */
        .status-indicator {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: rgba(16, 16, 26, 0.8);
            border-radius: 9999px;
            border: 1px solid rgba(0, 243, 255, 0.3);
        }
        
        .status-indicator::after {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            animation: pulse 2s infinite;
        }
        
        /* Model viewer container */
        .model-container {
            position: relative;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid rgba(0, 243, 255, 0.3);
            background: rgba(0, 0, 0, 0.3);
        }
        
        .model-container::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(45deg, transparent 40%, rgba(0, 243, 255, 0.1) 50%, transparent 60%);
            animation: shine 3s infinite;
            pointer-events: none;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        /* Terminal text */
        .terminal-text {
            font-family: 'Courier New', monospace;
            color: #00f3ff;
            text-shadow: 0 0 10px rgba(0, 243, 255, 0.5);
        }
        
        /* Ripple effect */
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        }
        
        /* Label styles */
        .cyber-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #00f3ff;
            font-family: 'Rajdhani', sans-serif;
        }
        
        /* Grid lines */
        .grid-lines {
            position: relative;
        }
        
        .grid-lines::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(to right, rgba(0, 243, 255, 0.1) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0, 243, 255, 0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            pointer-events: none;
            z-index: 0;
        }
    </style>
</head>
<body>
    <!-- Background elements -->
    <div class="cyber-bg"></div>
    <div class="glow-effect"></div>
    <div class="scanlines"></div>

    <!-- Main content -->
    <div class="main-content min-h-screen p-6">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-10 animate-fade-in">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-display font-black mb-2">
                            <span class="bg-gradient-to-r from-primary via-white to-accent bg-clip-text text-transparent">
                                <span class="terminal-text">$></span> BONJOUR, <?php echo htmlspecialchars(isset($profile['username']) ? strtoupper($profile['username']) : 'RUNNER'); ?>
                            </span>
                        </h1>
                        <div class="terminal-text text-lg">
                            <span class="text-primary">system@nightcity:~$</span>
                            <span class="ml-2 text-white">profile --status active</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="status-indicator">
                            <span class="text-xs font-display text-text-sub">STATUS:</span>
                            <span class="text-sm font-display text-primary font-bold">ACTIVE</span>
                        </div>
                        
                        <a href="/ChopinSoft/index.php?route=user/play" 
                           class="cyber-btn group relative flex items-center justify-center gap-3 px-8 py-3 rounded-lg">
                            <div class="absolute inset-0 bg-gradient-to-r from-primary/30 to-accent/30 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                            <span class="relative z-10 material-symbols-outlined text-sm">play_arrow</span>
                            <span class="relative z-10 text-sm">JOUER</span>
                        </a>
                    </div>
                </div>
                
                <div class="h-px bg-gradient-to-r from-transparent via-primary/50 to-transparent mb-8"></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Avatar Card -->
                <div class="cyber-card p-8 rounded-xl animate-slide-up" style="animation-delay: 0.1s">
                    <!-- Terminal header -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                            <span class="text-xs text-text-sub font-display">avatar_terminal</span>
                        </div>
                        <div class="h-px bg-gradient-to-r from-primary/50 via-accent/50 to-primary/50"></div>
                    </div>

                    <h2 class="text-2xl font-display font-bold mb-6 text-primary flex items-center gap-3">
                        <span class="material-symbols-outlined">person</span>
                        TON AVATAR
                    </h2>
                    
                    <!-- Current Avatar -->
                    <div class="mb-8">
                        <div class="model-container h-80 mb-4">
                            <?php if(isset($profile['modelAvatar']) && !empty($profile['modelAvatar'])): ?>
                                <model-viewer 
                                    src="<?php echo htmlspecialchars($profile['modelAvatar']); ?>" 
                                    alt="Avatar 3D Model"
                                    camera-controls 
                                    auto-rotate 
                                    camera-orbit="45deg 55deg 2.5m"
                                    exposure="0.8"
                                    shadow-intensity="1"
                                    style="width: 100%; height: 100%; background: transparent;"
                                >
                                </model-viewer>
                                <div style="font-size: 10px; color: #666; margin-top: 4px;">
                                    [DEBUG: <?php echo htmlspecialchars($profile['modelAvatar']); ?>]
                                </div>
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/10 to-accent/10">
                                    <div class="text-center">
                                        <span class="material-symbols-outlined text-6xl text-primary/50 block mb-4">person</span>
                                        <div style="font-size: 12px; color: #94a3b8;">Aucun modèle trouvé</div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="bg-surface-dark/50 p-4 rounded-lg border border-white/10">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="text-xs text-text-sub uppercase mb-1">AVATAR ACTUEL</div>
                                    <div class="text-xl font-display font-bold text-white">
                                        <?php echo htmlspecialchars(isset($profile['nameAvatar']) ? $profile['nameAvatar'] : 'NON DÉFINI'); ?>
                                    </div>
                                </div>
                                <div class="text-primary animate-pulse">
                                    <span class="material-symbols-outlined">sync</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Change Avatar Form -->
                    <div class="cyber-card p-6 rounded-lg border border-white/10">
                        <h3 class="text-xl font-display font-bold mb-4 text-accent flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">swap_horiz</span>
                            CHANGER D'AVATAR
                        </h3>
                        
                        <form method="post" action="/ChopinSoft/index.php?route=user/changeAvatar" class="space-y-6">
                            <div>
                                <label class="cyber-label">
                                    SÉLECTIONNER UN AVATAR
                                </label>
                                <select name="idAvatar" required class="cyber-select w-full">
                                    <?php foreach ($avatars as $a): ?>
                                        <option value="<?php echo (int)$a['idAvatar']; ?>" <?php echo (isset($profile['idAvatar']) && (int)$a['idAvatar'] === (int)$profile['idAvatar']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($a['nameAvatar']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="text-xs text-text-sub mt-2">
                                    Choisissez votre nouvelle identité virtuelle
                                </div>
                            </div>
                            
                            <div class="actions">
                                <button class="cyber-btn group relative flex items-center justify-center gap-3 w-full py-4 px-6 rounded-lg" type="submit">
                                    <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                                    <span class="relative z-10 material-symbols-outlined">change_circle</span>
                                    <span class="relative z-10">APPLIQUER LES CHANGEMENTS</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Worlds Card -->
                <div class="cyber-card p-8 rounded-xl animate-slide-up" style="animation-delay: 0.2s">
                    <!-- Terminal header -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                            <span class="text-xs text-text-sub font-display">worlds_terminal</span>
                        </div>
                        <div class="h-px bg-gradient-to-r from-primary/50 via-accent/50 to-primary/50"></div>
                    </div>

                    <h2 class="text-2xl font-display font-bold mb-6 text-primary flex items-center gap-3">
                        <span class="material-symbols-outlined">public</span>
                        MONDE DISPONIBLES
                    </h2>
                    
                    <form method="post" action="/ChopinSoft/index.php?route=user/changeWorld">
                        <div class="grid-lines relative p-6 rounded-lg bg-black/20 border border-white/10 mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 relative z-10">
                                <?php foreach ($worlds as $w): ?>
                                    <button class="world-btn group relative p-6 rounded-lg text-left transition-all duration-300" 
                                            type="submit" 
                                            name="idWorld" 
                                            value="<?php echo (int)$w['idWorld']; ?>">
                                        <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-accent/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        <div class="relative z-10">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-lg font-display font-bold text-white group-hover:text-primary transition-colors">
                                                    <?php echo htmlspecialchars($w['nameWorld']); ?>
                                                </span>
                                                <span class="material-symbols-outlined text-primary opacity-0 group-hover:opacity-100 transition-opacity">
                                                    arrow_forward
                                                </span>
                                            </div>
                                            <div class="text-sm text-text-sub">
                                                Connectez-vous à ce monde
                                            </div>
                                        </div>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-xs text-text-sub mb-2">
                                SÉLECTIONNEZ UN MONDE POUR VOUS CONNECTER
                            </div>
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-surface-dark/50 rounded-full">
                                <div class="w-2 h-2 rounded-full bg-primary animate-pulse"></div>
                                <span class="text-sm font-display"><?php echo count($worlds); ?> MONDE(S) DISPONIBLE(S)</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="mt-8 animate-slide-up" style="animation-delay: 0.3s">
                <div class="cyber-card p-6 rounded-xl">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-primary/20 to-accent/20 border border-primary/30 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-2xl">terminal</span>
                            </div>
                            <div>
                                <div class="text-sm text-text-sub uppercase">ACTION RAPIDE</div>
                            </div>
                        </div>
                        
                        <a href="/ChopinSoft/index.php?route=user/play" 
                           class="cyber-btn group relative flex items-center justify-center gap-3 px-10 py-4 rounded-lg bg-gradient-to-r from-primary via-accent to-cyber-purple text-white shadow-neon-primary hover:shadow-[0_0_40px_rgba(0,243,255,0.7)] hover:scale-105 transition-all">
                            <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                            <span class="relative z-10 material-symbols-outlined">rocket_launch</span>
                            <span class="relative z-10 text-lg">LANCER L'EXPÉRIENCE</span>
                            <span class="relative z-10 material-symbols-outlined transition-transform group-hover:translate-x-2">arrow_forward</span>
                        </a>
                    </div>
                </div>
                
                <!-- Back link -->
                <div class="mt-6 text-center">
                    <a href="/ChopinSoft/index.php?route=home/index" 
                       class="inline-flex items-center gap-2 text-sm text-text-sub hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Ripple effect on buttons
        document.querySelectorAll('button, a.cyber-btn').forEach(element => {
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
        
        // Form submission animation for avatar change
        const avatarForm = document.querySelector('form[action*="changeAvatar"]');
        if (avatarForm) {
            avatarForm.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                submitBtn.innerHTML = `
                    <span class="material-symbols-outlined animate-spin">refresh</span>
                    <span class="ml-2">MISE À JOUR DE L'AVATAR...</span>
                `;
                submitBtn.disabled = true;
                
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 3000);
            });
        }
        
        // Form submission animation for world change
        const worldForm = document.querySelector('form[action*="changeWorld"]');
        if (worldForm) {
            const worldButtons = worldForm.querySelectorAll('.world-btn');
            worldButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const originalText = this.innerHTML;
                    
                    this.innerHTML = `
                        <div class="relative z-10">
                            <div class="flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined animate-spin">sync</span>
                                <span>CONNEXION...</span>
                            </div>
                        </div>
                    `;
                    this.disabled = true;
                    
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 2000);
                });
            });
        }
        
        // Add hover effect to model viewer
        const modelViewer = document.querySelector('model-viewer');
        if (modelViewer) {
            modelViewer.addEventListener('mouseenter', () => {
                modelViewer.style.filter = 'drop-shadow(0 0 20px rgba(0, 243, 255, 0.5))';
            });
            
            modelViewer.addEventListener('mouseleave', () => {
                modelViewer.style.filter = 'none';
            });
        }
        
        // Add glitch effect randomly to title
        setInterval(() => {
            const title = document.querySelector('h1');
            if (title && Math.random() > 0.8) {
                title.style.animation = 'none';
                setTimeout(() => {
                    title.style.animation = 'glitch 0.3s';
                    setTimeout(() => {
                        title.style.animation = '';
                    }, 300);
                }, 10);
            }
        }, 5000);
        
        // Animate world buttons on load
        document.querySelectorAll('.world-btn').forEach((btn, index) => {
            btn.style.animationDelay = `${0.1 * index}s`;
            btn.classList.add('animate-slide-up');
        });
        
        // Update status indicator
        setInterval(() => {
            const statusDot = document.querySelector('.status-indicator::after');
            if (statusDot && Math.random() > 0.7) {
                statusDot.style.animation = 'none';
                setTimeout(() => {
                    statusDot.style.animation = 'pulse 2s infinite';
                }, 50);
            }
        }, 3000);
    </script>
</body>
</html>