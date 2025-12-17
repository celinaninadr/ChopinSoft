<!DOCTYPE html>
<html class="dark" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>CRUD Avatars - Night City</title>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
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
                        "danger": "#ef4444",
                        "warning": "#f59e0b",
                        "success": "#10b981",
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
                        "neon-danger": "0 0 15px rgba(239, 68, 68, 0.7), 0 0 30px rgba(239, 68, 68, 0.3)",
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s infinite',
                        'fade-in': 'fadeIn 0.8s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'float': 'float 6s ease-in-out infinite',
                        'hologram': 'hologram 3s ease-in-out infinite',
                        'rotate': 'rotate 20s linear infinite',
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
                        float: {
                            '0%, 100%': { transform: 'translateY(0) rotate(0deg)' },
                            '50%': { transform: 'translateY(-10px) rotate(1deg)' }
                        },
                        hologram: {
                            '0%, 100%': { opacity: '0.7' },
                            '50%': { opacity: '0.9' }
                        },
                        rotate: {
                            '0%': { transform: 'rotateY(0deg)' },
                            '100%': { transform: 'rotateY(360deg)' }
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
            transform: translateY(-5px);
        }
        
        /* Cyber input */
        .cyber-input {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 0.3rem;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .cyber-input:focus {
            outline: none;
            border-color: #00f3ff;
            box-shadow: 0 0 15px rgba(0, 243, 255, 0.5);
        }
        
        /* Cyber button */
        .cyber-btn {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
        
        /* Section headers */
        .section-header {
            position: relative;
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
        }
        
        .section-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #00f3ff, transparent);
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
        
        /* Avatar card */
        .avatar-card {
            position: relative;
            overflow: hidden;
        }
        
        .avatar-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 243, 255, 0.1), transparent);
            transition: left 0.7s;
        }
        
        .avatar-card:hover::before {
            left: 100%;
        }
        
        /* 3D Display Container */
        .display-3d {
            position: relative;
            width: 100%;
            height: 400px;
            border-radius: 0.5rem;
            overflow: hidden;
            margin: 1.5rem 0;
        }
        
        .display-frame {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 2px solid transparent;
            border-radius: 0.5rem;
            background: linear-gradient(135deg, rgba(0, 243, 255, 0.1), rgba(217, 70, 239, 0.1)) padding-box,
                        linear-gradient(135deg, #00f3ff, #d946ef, #9d00ff, #00f3ff) border-box;
            background-size: 300% 300%;
            animation: gradient 4s ease infinite;
            box-shadow: 
                inset 0 0 20px rgba(0, 243, 255, 0.2),
                0 0 30px rgba(0, 243, 255, 0.3);
            z-index: 2;
        }
        
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .hologram-effect {
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            background: linear-gradient(45deg, transparent 30%, rgba(0, 243, 255, 0.05) 50%, transparent 70%);
            border-radius: 0.4rem;
            animation: hologram 3s ease-in-out infinite;
            pointer-events: none;
            z-index: 3;
        }
        
        .model-container {
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border-radius: 0.4rem;
            overflow: hidden;
            z-index: 2;
        }
        
        model-viewer {
            width: 100%;
            height: 100%;
            --progress-bar-color: #00f3ff;
            --progress-bar-height: 3px;
            background: transparent;
        }
        
        /* Control panel */
        .control-panel {
            background: linear-gradient(135deg, rgba(22, 27, 46, 0.9), rgba(16, 16, 26, 0.95));
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .terminal-header {
            background: linear-gradient(90deg, #0b0b15, #161b2e);
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(0, 243, 255, 0.2);
            border-radius: 0.5rem 0.5rem 0 0;
        }
        
        .terminal-dots {
            display: flex;
            gap: 0.5rem;
        }
        
        .terminal-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }
        
        .terminal-dot.red { background: #ef4444; }
        .terminal-dot.yellow { background: #f59e0b; }
        .terminal-dot.green { background: #10b981; }
        
        /* Badge */
        .avatar-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.75rem;
            background: rgba(217, 70, 239, 0.1);
            border: 1px solid rgba(217, 70, 239, 0.3);
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #d946ef;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        /* Empty state */
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            border: 2px dashed rgba(217, 70, 239, 0.2);
            border-radius: 0.5rem;
            background: rgba(0, 0, 0, 0.2);
        }
        
        /* Loading spinner */
        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 50px;
            height: 50px;
            border: 3px solid rgba(0, 243, 255, 0.1);
            border-top: 3px solid #00f3ff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            z-index: 5;
        }
        
        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Background elements -->
    <div class="cyber-bg"></div>

    <!-- Main content -->
    <div class="main-content min-h-screen p-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-8 animate-fade-in">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-display font-black mb-2">
                            <span class="bg-gradient-to-r from-accent via-white to-cyber-purple bg-clip-text text-transparent">
                                GESTION DES AVATARS
                            </span>
                        </h1>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                            <span class="text-sm text-text-sub font-display">AVATAR CONTROL PANEL</span>
                            <div class="avatar-badge ml-4">
                                <span class="material-symbols-outlined text-sm">diversity</span>
                                <span><?php echo count($avatars); ?> avatars</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="/ChopinSoft/index.php?route=admin/users" 
                           class="flex items-center gap-2 px-4 py-2 rounded-lg bg-surface-dark border border-white/10 text-text-sub hover:text-white hover:border-primary/50 transition-all">
                            <span class="material-symbols-outlined">arrow_back</span>
                            <span>Utilisateurs</span>
                        </a>
                        <a href="/ChopinSoft/index.php?route=admin/worlds" 
                           class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-primary/20 to-cyber-blue/20 border border-primary/30 text-text-sub hover:text-white hover:border-primary/50 transition-all">
                            <span class="material-symbols-outlined">public</span>
                            <span>Mondes</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Control Panel -->
            <div class="control-panel animate-slide-up" style="animation-delay: 0.2s">
                <div class="terminal-header mb-4">
                    <div class="flex justify-between items-center">
                        <div class="terminal-dots">
                            <div class="terminal-dot red"></div>
                            <div class="terminal-dot yellow"></div>
                            <div class="terminal-dot green"></div>
                        </div>
                        <span class="text-xs text-text-sub font-display">avatars@system:~</span>
                    </div>
                </div>
                
                <p class="text-text-sub mb-6">
                    Gérer les modèles 3D d'avatar. Chaque avatar est un modèle .glb utilisable par les joueurs dans Night City.
                </p>
            </div>

            <!-- Add Avatar Form -->
            <div class="cyber-card p-6 rounded-xl mb-8 animate-slide-up" style="animation-delay: 0.4s">
                <div class="section-header">
                    <h2 class="text-xl font-display font-bold text-white">AJOUTER UN AVATAR</h2>
                </div>
                
                <form method="post">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-display font-bold uppercase tracking-wider text-primary mb-3">
                                NOM DE L'AVATAR
                            </label>
                            <input type="text" 
                                   name="nameAvatar" 
                                   required 
                                   class="cyber-input w-full"
                                   placeholder="Entrer le nom de l'avatar">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-display font-bold uppercase tracking-wider text-primary mb-3">
                                FICHIER 3D (.glb URL)
                            </label>
                            <input type="url" 
                                   name="modelAvatar" 
                                   required 
                                   class="cyber-input w-full"
                                   placeholder="https://exemple.com/modele.glb"
                                   pattern=".*\.glb$">
                            <div class="text-xs text-text-sub mt-2">
                                URL d'un fichier .glb 3D
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-8">
                        <label class="block text-sm font-display font-bold uppercase tracking-wider text-primary mb-3">
                            IMAGE (URL optionnelle)
                        </label>
                        <input type="url" 
                               name="imgAvatar" 
                               class="cyber-input w-full"
                               placeholder="https://exemple.com/image.jpg">
                        <div class="text-xs text-text-sub mt-2">
                            URL d'une image de prévisualisation (optionnel)
                        </div>
                    </div>
                    
                    <button type="submit" 
                            class="cyber-btn group relative flex items-center justify-center gap-3 w-full py-4 px-6 rounded-lg bg-gradient-to-r from-success to-cyber-purple text-white font-display font-bold tracking-wider uppercase shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:shadow-[0_0_30px_rgba(16,185,129,0.6)] transition-all">
                        <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <span class="relative z-10 material-symbols-outlined">add_circle</span>
                        <span class="relative z-10">CRÉER L'AVATAR</span>
                        <span class="relative z-10 material-symbols-outlined transition-transform group-hover:translate-x-2">arrow_forward</span>
                    </button>
                </form>
            </div>

            <!-- Avatars List -->
            <div class="animate-slide-up" style="animation-delay: 0.6s">
                <div class="section-header">
                    <h2 class="text-xl font-display font-bold text-white">LISTE DES AVATARS</h2>
                    <div class="text-sm text-text-sub mt-1">
                        <?php echo count($avatars); ?> modèles 3D disponibles
                    </div>
                </div>
                
                <?php if (empty($avatars)): ?>
                    <div class="empty-state">
                        <span class="material-symbols-outlined text-6xl text-text-sub mb-4">person_off</span>
                        <p class="text-2xl text-text-sub font-display mb-2">AUCUN AVATAR</p>
                        <p class="text-text-sub">Créez votre premier modèle 3D pour commencer</p>
                    </div>
                <?php else: ?>
                    <div class="grid md:grid-cols-2 gap-6">
                        <?php foreach ($avatars as $a): ?>
                            <div class="avatar-card cyber-card p-6 rounded-xl animate-float" style="animation-delay: <?php echo (int)$a['idAvatar'] * 0.1; ?>s">
                                <!-- Avatar ID Badge -->
                                <div class="absolute top-4 right-4 avatar-badge">
                                    <span class="material-symbols-outlined text-sm">tag</span>
                                    <span>ID: <?php echo (int)$a['idAvatar']; ?></span>
                                </div>
                                
                                <!-- Avatar Name -->
                                <div class="mb-4">
                                    <h3 class="text-xl font-display font-bold text-accent mb-2">
                                        <?php echo htmlspecialchars($a['nameAvatar']); ?>
                                    </h3>
                                </div>
                                
                                <!-- 3D Preview Display -->
                                <div class="display-3d">
                                    <div class="display-frame"></div>
                                    <div class="hologram-effect"></div>
                                    <div class="model-container">
                                        <model-viewer 
                                            id="avatar-model-<?php echo (int)$a['idAvatar']; ?>"
                                            src="<?php echo htmlspecialchars($a['modelAvatar']); ?>"
                                            camera-controls 
                                            auto-rotate
                                            auto-rotate-delay="0"
                                            shadow-intensity="1.5"
                                            exposure="1.2"
                                            camera-orbit="0deg 75deg 2.5m"
                                            interaction-prompt="none"
                                            loading="eager"
                                        >
                                            <div class="loading-spinner"></div>
                                        </model-viewer>
                                    </div>
                                </div>
                                
                                <!-- Edit Form -->
                                <form method="post" class="mb-4">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="idAvatar" value="<?php echo (int)$a['idAvatar']; ?>">
                                    
                                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-xs font-display font-bold uppercase tracking-wider text-primary mb-2">
                                                NOM
                                            </label>
                                            <input type="text" 
                                                   name="nameAvatar" 
                                                   value="<?php echo htmlspecialchars($a['nameAvatar']); ?>" 
                                                   required 
                                                   class="cyber-input w-full text-sm"
                                                   oninput="updateAvatarName(this, <?php echo (int)$a['idAvatar']; ?>)">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-xs font-display font-bold uppercase tracking-wider text-primary mb-2">
                                                FICHIER 3D
                                            </label>
                                            <input type="url" 
                                                   name="modelAvatar" 
                                                   value="<?php echo htmlspecialchars($a['modelAvatar']); ?>" 
                                                   required 
                                                   class="cyber-input w-full text-sm"
                                                   pattern=".*\.glb$"
                                                   onblur="updateModelPreview(this, <?php echo (int)$a['idAvatar']; ?>)">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="block text-xs font-display font-bold uppercase tracking-wider text-primary mb-2">
                                            IMAGE
                                        </label>
                                        <input type="url" 
                                               name="imgAvatar" 
                                               value="<?php echo htmlspecialchars($a['imgAvatar'] ?? ''); ?>" 
                                               class="cyber-input w-full text-sm"
                                               placeholder="URL de l'image">
                                    </div>
                                    
                                    <button type="submit" 
                                            class="cyber-btn w-full py-3 px-6 rounded-lg bg-gradient-to-r from-warning to-cyber-purple text-white font-display font-bold text-sm tracking-wider uppercase shadow-neon-warning hover:shadow-[0_0_20px_rgba(245,158,11,0.6)] transition-all">
                                        MODIFIER
                                    </button>
                                </form>
                                
                                <!-- Delete Form -->
                                <form method="post" 
                                      onsubmit="return confirmDeleteAvatar('<?php echo htmlspecialchars(addslashes($a['nameAvatar'])); ?>')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="idAvatar" value="<?php echo (int)$a['idAvatar']; ?>">
                                    
                                    <button type="submit" 
                                            class="cyber-btn w-full py-3 px-6 rounded-lg bg-gradient-to-r from-danger to-cyber-purple text-white font-display font-bold text-sm tracking-wider uppercase shadow-neon-danger hover:shadow-[0_0_20px_rgba(239,68,68,0.6)] transition-all">
                                        SUPPRIMER
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Ripple effect on buttons
        document.querySelectorAll('.cyber-btn, a').forEach(element => {
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
        
        // Delete confirmation with avatar name
        function confirmDeleteAvatar(avatarName) {
            return confirm(`⚠️ Confirmer la suppression de l'avatar "${avatarName}" ?\n\nCette action est irréversible et affectera les utilisateurs utilisant cet avatar.`);
        }
        
        // Update avatar name in real-time
        function updateAvatarName(input, avatarId) {
            const avatarTitle = input.closest('.avatar-card').querySelector('h3');
            if (avatarTitle) {
                avatarTitle.textContent = input.value;
                
                // Add visual feedback
                avatarTitle.style.color = '#00f3ff';
                setTimeout(() => {
                    avatarTitle.style.color = '#d946ef';
                }, 500);
            }
        }
        
        // Update model preview on URL change
        function updateModelPreview(input, avatarId) {
            const modelViewer = document.getElementById(`avatar-model-${avatarId}`);
            if (modelViewer && input.value) {
                // Show loading
                modelViewer.style.opacity = '0.5';
                
                // Change model source
                setTimeout(() => {
                    modelViewer.src = input.value;
                    modelViewer.style.opacity = '1';
                    
                    // Add transition effect
                    modelViewer.animate([
                        { opacity: 0 },
                        { opacity: 1 }
                    ], {
                        duration: 500,
                        easing: 'ease-in-out'
                    });
                }, 300);
            }
        }
        
        // Form submission animations
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    const originalText = submitBtn.innerHTML;
                    const action = this.querySelector('input[name="action"]').value;
                    
                    if (action === 'delete') {
                        submitBtn.innerHTML = `
                            <span class="material-symbols-outlined animate-spin">delete</span>
                            <span class="ml-2">SUPPRESSION...</span>
                        `;
                    } else if (action === 'update') {
                        submitBtn.innerHTML = `
                            <span class="material-symbols-outlined animate-spin">sync</span>
                            <span class="ml-2">MISE À JOUR...</span>
                        `;
                    } else {
                        submitBtn.innerHTML = `
                            <span class="material-symbols-outlined animate-spin">add_circle</span>
                            <span class="ml-2">CRÉATION...</span>
                        `;
                    }
                    
                    submitBtn.disabled = true;
                    
                    // Revert after 3 seconds if still on page
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 3000);
                }
            });
        });
        
        // Add loading state to model viewers
        document.querySelectorAll('model-viewer').forEach(viewer => {
            viewer.addEventListener('load', () => {
                const spinner = viewer.querySelector('.loading-spinner');
                if (spinner) {
                    spinner.style.opacity = '0';
                    setTimeout(() => {
                        spinner.style.display = 'none';
                    }, 500);
                }
                
                // Add success animation
                viewer.style.boxShadow = 'inset 0 0 30px rgba(0, 243, 255, 0.2)';
                setTimeout(() => {
                    viewer.style.boxShadow = '';
                }, 1500);
            });
            
            viewer.addEventListener('error', () => {
                const spinner = viewer.querySelector('.loading-spinner');
                if (spinner) {
                    spinner.style.borderTopColor = '#ef4444';
                }
                
                // Show error state
                viewer.style.border = '2px dashed #ef4444';
                viewer.style.background = 'rgba(239, 68, 68, 0.1)';
            });
        });
        
        // Add hover effects to avatar cards
        document.querySelectorAll('.avatar-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                const modelViewer = this.querySelector('model-viewer');
                if (modelViewer) {
                    modelViewer.autoRotate = true;
                }
            });
            
            card.addEventListener('mouseleave', function() {
                const modelViewer = this.querySelector('model-viewer');
                if (modelViewer) {
                    // Keep auto-rotate enabled by default
                    modelViewer.autoRotate = true;
                }
            });
        });
        
        // Real-time avatar count animation
        const avatarCount = document.querySelector('.avatar-badge span:last-child');
        if (avatarCount) {
            const originalCount = <?php echo count($avatars); ?>;
            let animatedCount = originalCount;
            
            setInterval(() => {
                if (Math.random() > 0.8) {
                    // Simulate avatar activity
                    const badge = avatarCount.closest('.avatar-badge');
                    badge.style.background = 'rgba(217, 70, 239, 0.2)';
                    badge.style.boxShadow = '0 0 10px rgba(217, 70, 239, 0.5)';
                    
                    setTimeout(() => {
                        badge.style.background = 'rgba(217, 70, 239, 0.1)';
                        badge.style.boxShadow = 'none';
                    }, 500);
                }
            }, 8000);
        }
        
        // URL validation for .glb files
        document.querySelectorAll('input[name="modelAvatar"]').forEach(input => {
            input.addEventListener('change', function() {
                if (this.value && !this.value.endsWith('.glb')) {
                    this.style.borderColor = '#f59e0b';
                    this.style.boxShadow = '0 0 10px rgba(245, 158, 11, 0.5)';
                    
                    const warning = document.createElement('div');
                    warning.className = 'text-xs text-warning mt-1 flex items-center gap-1';
                    warning.innerHTML = `
                        <span class="material-symbols-outlined text-sm">warning</span>
                        L'URL doit pointer vers un fichier .glb
                    `;
                    
                    const existingWarning = this.nextElementSibling;
                    if (existingWarning && existingWarning.className.includes('warning')) {
                        existingWarning.remove();
                    }
                    
                    this.parentNode.insertBefore(warning, this.nextSibling);
                    
                    setTimeout(() => {
                        this.style.borderColor = '';
                        this.style.boxShadow = '';
                        if (warning.parentNode) {
                            warning.remove();
                        }
                    }, 3000);
                }
            });
        });
        
        // Auto-preview for new avatar form
        const modelInput = document.querySelector('input[name="modelAvatar"]:not([value])');
        if (modelInput) {
            const previewContainer = document.createElement('div');
            previewContainer.className = 'mt-4 p-4 bg-black/30 rounded border border-white/10 hidden';
            previewContainer.innerHTML = `
                <div class="text-sm text-text-sub uppercase tracking-wider mb-2">APERÇU 3D</div>
                <div class="h-48 rounded bg-surface-light border border-white/10 flex items-center justify-center">
                    <div class="text-center">
                        <span class="material-symbols-outlined text-4xl text-text-sub mb-2">model_viewer</span>
                        <p class="text-sm text-text-sub">Aperçu disponible après saisie</p>
                    </div>
                </div>
            `;
            
            modelInput.parentNode.insertBefore(previewContainer, modelInput.nextSibling);
            
            modelInput.addEventListener('input', function() {
                if (this.value) {
                    previewContainer.classList.remove('hidden');
                } else {
                    previewContainer.classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html>