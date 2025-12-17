<!DOCTYPE html>
<html class="dark" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>CRUD Mondes - Night City</title>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
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
                        'portal-glow': 'portalGlow 2s ease-in-out infinite',
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
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        portalGlow: {
                            '0%, 100%': { boxShadow: '0 0 20px rgba(0, 243, 255, 0.5), 0 0 40px rgba(0, 243, 255, 0.3)' },
                            '50%': { boxShadow: '0 0 30px rgba(0, 243, 255, 0.8), 0 0 60px rgba(0, 243, 255, 0.5)' }
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
        
        /* World card */
        .world-card {
            position: relative;
            overflow: hidden;
        }
        
        .world-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 243, 255, 0.1), transparent);
            transition: left 0.7s;
        }
        
        .world-card:hover::before {
            left: 100%;
        }
        
        /* Portal button */
        .portal-btn {
            animation: portalGlow 2s ease-in-out infinite;
        }
        
        /* World preview */
        .world-preview {
            position: relative;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 2px solid rgba(0, 243, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .world-preview:hover {
            border-color: #00f3ff;
            box-shadow: 0 0 30px rgba(0, 243, 255, 0.5);
        }
        
        .world-preview::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 60%, rgba(0, 243, 255, 0.1));
            pointer-events: none;
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
        
        /* Empty state */
        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            border: 2px dashed rgba(0, 243, 255, 0.2);
            border-radius: 0.5rem;
            background: rgba(0, 0, 0, 0.2);
        }
        
        /* Badge */
        .world-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.75rem;
            background: rgba(0, 243, 255, 0.1);
            border: 1px solid rgba(0, 243, 255, 0.3);
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #00f3ff;
            text-transform: uppercase;
            letter-spacing: 0.05em;
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
                            <span class="bg-gradient-to-r from-primary via-white to-accent bg-clip-text text-transparent">
                                GESTION DES MONDES
                            </span>
                        </h1>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                            <span class="text-sm text-text-sub font-display">SYSTEM CONTROL PANEL</span>
                            <div class="world-badge ml-4">
                                <span class="material-symbols-outlined text-sm">public</span>
                                <span><?php echo count($worlds); ?> mondes</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="/ChopinSoft/index.php?route=admin/users" 
                           class="flex items-center gap-2 px-4 py-2 rounded-lg bg-surface-dark border border-white/10 text-text-sub hover:text-white hover:border-primary/50 transition-all">
                            <span class="material-symbols-outlined">arrow_back</span>
                            <span>Utilisateurs</span>
                        </a>
                        <a href="/ChopinSoft/index.php?route=admin/avatars" 
                           class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-accent/20 to-cyber-purple/20 border border-accent/30 text-text-sub hover:text-white hover:border-accent/50 transition-all">
                            <span class="material-symbols-outlined">person</span>
                            <span>Avatars</span>
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
                        <span class="text-xs text-text-sub font-display">worlds@system:~</span>
                    </div>
                </div>
                
                <p class="text-text-sub mb-6">
                    Gérer les portails vers les mondes virtuels. Chaque monde représente un environnement VR accessible aux utilisateurs.
                </p>
            </div>

            <!-- Add World Form -->
            <div class="cyber-card p-6 rounded-xl mb-8 animate-slide-up" style="animation-delay: 0.4s">
                <div class="section-header">
                    <h2 class="text-xl font-display font-bold text-white">AJOUTER UN MONDE</h2>
                </div>
                
                <form method="post">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-display font-bold uppercase tracking-wider text-primary mb-3">
                                NOM DU MONDE
                            </label>
                            <input type="text" 
                                   name="nameWorld" 
                                   required 
                                   class="cyber-input w-full"
                                   placeholder="Entrer le nom du monde">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-display font-bold uppercase tracking-wider text-primary mb-3">
                                URL DU PORTAIL
                            </label>
                            <input type="url" 
                                   name="urlWorld" 
                                   required 
                                   class="cyber-input w-full"
                                   placeholder="https://exemple.com">
                        </div>
                    </div>
                    
                    <div class="mb-8">
                        <label class="block text-sm font-display font-bold uppercase tracking-wider text-primary mb-3">
                            IMAGE (URL)
                        </label>
                        <input type="url" 
                               name="imgWorld" 
                               class="cyber-input w-full"
                               placeholder="https://exemple.com/image.jpg">
                        <div class="text-xs text-text-sub mt-2">
                            URL d'une image pour la prévisualisation du monde (optionnel)
                        </div>
                    </div>
                    
                    <button type="submit" 
                            class="cyber-btn group relative flex items-center justify-center gap-3 w-full py-4 px-6 rounded-lg bg-gradient-to-r from-success to-cyber-blue text-white font-display font-bold tracking-wider uppercase shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:shadow-[0_0_30px_rgba(16,185,129,0.6)] transition-all">
                        <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <span class="relative z-10 material-symbols-outlined">add</span>
                        <span class="relative z-10">CRÉER LE MONDE</span>
                        <span class="relative z-10 material-symbols-outlined transition-transform group-hover:translate-x-2">arrow_forward</span>
                    </button>
                </form>
            </div>

            <!-- Worlds List -->
            <div class="animate-slide-up" style="animation-delay: 0.6s">
                <div class="section-header">
                    <h2 class="text-xl font-display font-bold text-white">LISTE DES MONDES</h2>
                    <div class="text-sm text-text-sub mt-1">
                        <?php echo count($worlds); ?> mondes disponibles
                    </div>
                </div>
                
                <?php if (empty($worlds)): ?>
                    <div class="empty-state">
                        <span class="material-symbols-outlined text-6xl text-text-sub mb-4">public_off</span>
                        <p class="text-2xl text-text-sub font-display mb-2">AUCUN MONDE</p>
                        <p class="text-text-sub">Créez votre premier monde pour commencer</p>
                    </div>
                <?php else: ?>
                    <div class="grid md:grid-cols-2 gap-6">
                        <?php foreach ($worlds as $w): ?>
                            <div class="world-card cyber-card p-6 rounded-xl animate-float" style="animation-delay: <?php echo (int)$w['idWorld'] * 0.1; ?>s">
                                <!-- World ID Badge -->
                                <div class="absolute top-4 right-4 world-badge">
                                    <span class="material-symbols-outlined text-sm">tag</span>
                                    <span>ID: <?php echo (int)$w['idWorld']; ?></span>
                                </div>
                                
                                <!-- Preview -->
                                <?php if (!empty($w['imgWorld'])): ?>
                                    <div class="mb-6">
                                        <div class="text-sm text-text-sub uppercase tracking-wider mb-3">PRÉVISUALISATION</div>
                                        <div class="world-preview">
                                            <img src="<?php echo htmlspecialchars($w['imgWorld']); ?>" 
                                                 alt="<?php echo htmlspecialchars($w['nameWorld']); ?>" 
                                                 class="w-full h-48 object-cover">
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Portal Button -->
                                <div class="mb-6">
                                    <a class="portal-btn cyber-btn inline-flex items-center justify-center gap-2 w-full py-3 px-6 rounded-lg bg-gradient-to-r from-primary to-cyber-blue text-white font-display font-bold tracking-wider uppercase shadow-neon-primary hover:shadow-[0_0_40px_rgba(0,243,255,0.7)] transition-all"
                                       target="_blank" 
                                       href="<?php echo htmlspecialchars($w['urlWorld']); ?>">
                                        <span class="material-symbols-outlined">open_in_new</span>
                                        OUVRIR LE PORTAIL
                                    </a>
                                </div>
                                
                                <!-- Edit Form -->
                                <form method="post" class="mb-4">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="idWorld" value="<?php echo (int)$w['idWorld']; ?>">
                                    
                                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label class="block text-xs font-display font-bold uppercase tracking-wider text-primary mb-2">
                                                NOM
                                            </label>
                                            <input type="text" 
                                                   name="nameWorld" 
                                                   value="<?php echo htmlspecialchars($w['nameWorld']); ?>" 
                                                   required 
                                                   class="cyber-input w-full text-sm">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-xs font-display font-bold uppercase tracking-wider text-primary mb-2">
                                                URL
                                            </label>
                                            <input type="url" 
                                                   name="urlWorld" 
                                                   value="<?php echo htmlspecialchars($w['urlWorld']); ?>" 
                                                   required 
                                                   class="cyber-input w-full text-sm">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="block text-xs font-display font-bold uppercase tracking-wider text-primary mb-2">
                                            IMAGE
                                        </label>
                                        <input type="url" 
                                               name="imgWorld" 
                                               value="<?php echo htmlspecialchars($w['imgWorld'] ?? ''); ?>" 
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
                                      onsubmit="return confirmDeleteWorld('<?php echo htmlspecialchars(addslashes($w['nameWorld'])); ?>')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="idWorld" value="<?php echo (int)$w['idWorld']; ?>">
                                    
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
        
        // Delete confirmation with world name
        function confirmDeleteWorld(worldName) {
            return confirm(`⚠️ Confirmer la suppression du monde "${worldName}" ?\n\nCette action est irréversible et affectera les utilisateurs liés à ce monde.`);
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
                            <span class="material-symbols-outlined animate-spin">add</span>
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
        
        // Image preview on URL change
        document.querySelectorAll('input[name="imgWorld"]').forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value) {
                    const card = this.closest('.world-card');
                    if (card) {
                        const preview = card.querySelector('.world-preview');
                        if (preview) {
                            const img = preview.querySelector('img');
                            img.src = this.value;
                        }
                    }
                }
            });
        });
        
        // Add hover effects to world cards
        document.querySelectorAll('.world-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                const portalBtn = this.querySelector('.portal-btn');
                if (portalBtn) {
                    portalBtn.style.transform = 'scale(1.05)';
                }
            });
            
            card.addEventListener('mouseleave', function() {
                const portalBtn = this.querySelector('.portal-btn');
                if (portalBtn) {
                    portalBtn.style.transform = 'scale(1)';
                }
            });
        });
        
        // Real-time world count animation
        const worldCount = document.querySelector('.world-badge span:last-child');
        if (worldCount) {
            const originalCount = <?php echo count($worlds); ?>;
            let animatedCount = originalCount;
            
            setInterval(() => {
                if (Math.random() > 0.8) {
                    // Simulate portal activity
                    const badge = worldCount.closest('.world-badge');
                    badge.style.background = 'rgba(0, 243, 255, 0.2)';
                    badge.style.boxShadow = '0 0 10px rgba(0, 243, 255, 0.5)';
                    
                    setTimeout(() => {
                        badge.style.background = 'rgba(0, 243, 255, 0.1)';
                        badge.style.boxShadow = 'none';
                    }, 500);
                }
            }, 8000);
        }
        
        // URL validation for portal links
        document.querySelectorAll('input[name="urlWorld"]').forEach(input => {
            input.addEventListener('change', function() {
                if (this.value && !this.value.startsWith('http')) {
                    this.style.borderColor = '#f59e0b';
                    this.style.boxShadow = '0 0 10px rgba(245, 158, 11, 0.5)';
                    
                    setTimeout(() => {
                        this.style.borderColor = '';
                        this.style.boxShadow = '';
                    }, 2000);
                }
            });
        });
        
        // Auto-preview for new world form
        const imgInput = document.querySelector('input[name="imgWorld"]');
        if (imgInput && !imgInput.closest('.world-card')) {
            const previewContainer = document.createElement('div');
            previewContainer.className = 'mt-4 p-4 bg-black/30 rounded border border-white/10 hidden';
            previewContainer.innerHTML = `
                <div class="text-sm text-text-sub uppercase tracking-wider mb-2">APERÇU</div>
                <img id="live-preview" class="w-full h-40 object-cover rounded" src="" alt="Aperçu">
            `;
            
            imgInput.parentNode.insertBefore(previewContainer, imgInput.nextSibling);
            
            imgInput.addEventListener('input', function() {
                if (this.value) {
                    previewContainer.classList.remove('hidden');
                    document.getElementById('live-preview').src = this.value;
                } else {
                    previewContainer.classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html>