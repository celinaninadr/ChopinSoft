<!DOCTYPE html>
<html class="dark" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Night City VR</title>
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
                        "cyber-purple": "#9d00ff",
                        "cyber-blue": "#0066ff",
                        "cyber-pink": "#ff00cc",
                        "danger": "#ef4444"
                    },
                    fontFamily: {
                        "display": ["Rajdhani", "sans-serif"],
                    },
                    borderRadius: {"DEFAULT": "0.3rem", "lg": "0.5rem", "xl": "1rem", "full": "9999px"},
                    boxShadow: {
                        "neon-primary": "0 0 15px rgba(0, 243, 255, 0.7), 0 0 30px rgba(0, 243, 255, 0.3)",
                        "neon-accent": "0 0 15px rgba(217, 70, 239, 0.7), 0 0 30px rgba(217, 70, 239, 0.3)",
                        "neon-purple": "0 0 15px rgba(157, 0, 255, 0.7), 0 0 30px rgba(157, 0, 255, 0.3)",
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'fade-in': 'fadeIn 0.8s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'neon-pulse': 'neonPulse 2s infinite',
                        'spin-slow': 'spin 3s linear infinite',
                        'typing': 'typing 3.5s steps(40, end), blink .75s step-end infinite',
                        'slide-in-right': 'slideInRight 0.8s ease-out',
                        'gradient': 'gradient 3s ease infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        slideInRight: {
                            '0%': { transform: 'translateX(30px)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' }
                        },
                        neonPulse: {
                            '0%, 100%': { opacity: '1' },
                            '50%': { opacity: '0.7' }
                        },
                        typing: {
                            'from': { width: '0' },
                            'to': { width: '100%' }
                        },
                        blink: {
                            'from, to': { 'border-color': 'transparent' },
                            '50%': { 'border-color': '#00f3ff' }
                        },
                        gradient: {
                            '0%, 100%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' }
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
        
        .glow-effect {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 800px;
            height: 800px;
            background: radial-gradient(circle, rgba(0, 243, 255, 0.1) 0%, transparent 70%);
            filter: blur(100px);
            pointer-events: none;
            z-index: 0;
        }
        
        /* Main content */
        .main-content {
            position: relative;
            z-index: 10;
        }
        
        /* Terminal window */
        .terminal-window {
            background: rgba(5, 5, 10, 0.95);
            border: 1px solid rgba(0, 243, 255, 0.3);
            border-radius: 0.5rem;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        
        .terminal-header {
            background: linear-gradient(90deg, #0b0b15, #161b2e);
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(0, 243, 255, 0.2);
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
        
        /* Music player */
        .music-player {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 100;
            opacity: 0.7;
            transition: opacity 0.3s;
        }
        
        .music-player:hover {
            opacity: 1;
        }
        
        .music-toggle {
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(0, 243, 255, 0.3);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .music-toggle:hover {
            border-color: rgba(0, 243, 255, 0.7);
            box-shadow: 0 0 15px rgba(0, 243, 255, 0.5);
            transform: scale(1.1);
        }
        
        /* Join button animation */
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(0, 243, 255, 0.5); }
            50% { box-shadow: 0 0 40px rgba(0, 243, 255, 0.8); }
        }
        
        .join-btn {
            animation: pulse-glow 2s infinite;
        }
        
        /* Typing cursor */
        .typing-cursor {
            display: inline-block;
            width: 8px;
            height: 1em;
            background-color: #00f3ff;
            margin-left: 4px;
            animation: blink 1s infinite;
            vertical-align: middle;
        }
        
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
    </style>
</head>
<body>
    <!-- Background elements -->
    <div class="cyber-bg"></div>
    <div class="glow-effect"></div>
    
    <!-- Music player -->
    <div class="music-player">
        <div class="music-toggle" onclick="toggleMusic()">
            <span class="material-symbols-outlined text-primary text-2xl" id="music-icon">music_note</span>
        </div>
        <audio id="bg-music" loop>
            <source src="https://www.chosic.com/wp-content/uploads/2021/07/cyberpunk.mp3" type="audio/mpeg">
        </audio>
    </div>

    <!-- Main content -->
    <div class="main-content min-h-screen flex flex-col">
        <main class="flex-1 flex items-center justify-center py-8">
            <div class="max-w-6xl w-full px-4">
                <!-- Hero Section -->
                <div class="text-center mb-12 animate-fade-in">
                    
                    <!-- Title centered -->
                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-display font-black mb-8">
                        <span class="bg-gradient-to-r from-primary via-white to-accent bg-clip-text text-transparent">
                            PLONGEZ DANS L'IMMERSION
                        </span>
                    </h1>
                    
                    <!-- Terminal window -->
                    <div class="terminal-window max-w-3xl mx-auto mb-6 animate-slide-up" style="animation-delay: 0.3s">
                        <div class="terminal-header">
                            <div class="flex justify-between items-center">
                                <div class="terminal-dots">
                                    <div class="terminal-dot red"></div>
                                    <div class="terminal-dot yellow"></div>
                                    <div class="terminal-dot green"></div>
                                </div>
                                <span class="text-xs text-text-sub font-display">system@vr:~</span>
                            </div>
                        </div>
                        <div class="p-6 font-mono">
                            <div class="mb-4">
                                <span class="text-primary">>></span>
                                <span class="ml-2 text-white" id="typing-text"></span>
                                <span class="typing-cursor"></span>
                            </div>
                            <div class="space-y-2 text-left">
                                <p class="text-primary">✓ Réalité virtuelle: <span class="text-white">ACTIVÉE</span></p>
                                <p class="text-primary">✓ Environnement: <span class="text-white">CHARGÉ</span></p>
                                <p class="text-primary">✓ Prêt pour immersion: <span class="text-white">OUI</span></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description text -->
                    <p class="text-lg text-text-sub max-w-2xl mx-auto mb-8 animate-slide-up" style="animation-delay: 0.5s">
                        L'expérience de réalité virtuelle ultime vous attend. 
                        <span class="text-primary font-bold">Créez votre avatar</span>, explorez des mondes infinis et vivez l'aventure.
                    </p>
                    
                    <!-- Join Button -->
                    <div class="mt-8 animate-slide-up" style="animation-delay: 0.7s">
                        <?php if (empty($_SESSION['user'])): ?>
                            <a href="/ChopinSoft/index.php?route=user/create"
                               class="join-btn cyber-btn inline-flex items-center justify-center gap-3 px-12 py-6 rounded-xl bg-gradient-to-r from-primary via-accent to-cyber-purple text-white font-display font-black text-xl tracking-wider uppercase hover:shadow-[0_0_50px_rgba(0,243,255,0.8)] transition-all transform hover:scale-105">
                                <span class="material-symbols-outlined text-2xl">rocket_launch</span>
                                <span>COMMENCER L'AVENTURE</span>
                                <span class="material-symbols-outlined text-2xl transition-transform group-hover:translate-x-2">arrow_forward</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Action Cards -->
                <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto mt-8">
                    <?php if (!empty($_SESSION['user'])): ?>
                        <!-- User is logged in -->
                        <?php if (($_SESSION['user']['userRole'] ?? '') === 'ADMIN'): ?>
                            <!-- Admin Card -->
                            <div class="cyber-card p-8 rounded-xl animate-slide-in-right" style="animation-delay: 0.7s">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="flex items-center justify-center size-14 rounded-full bg-gradient-to-r from-danger to-cyber-purple">
                                        <span class="material-symbols-outlined text-white text-2xl">admin_panel_settings</span>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-display font-bold text-white">ADMINISTRATION</h3>
                                        <p class="text-text-sub">Contrôle système</p>
                                    </div>
                                </div>
                                <p class="text-text-sub mb-8">
                                    Interface de gestion complète pour administrer l'environnement VR.
                                </p>
                                <a href="/ChopinSoft/index.php?route=admin/users"
                                   class="cyber-btn group relative flex items-center justify-center gap-3 w-full py-4 px-6 rounded-lg bg-gradient-to-r from-danger to-cyber-purple text-white font-display font-bold tracking-wider uppercase shadow-[0_0_20px_rgba(239,68,68,0.3)] hover:shadow-[0_0_30px_rgba(239,68,68,0.6)] transition-all">
                                    <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                                    <span class="relative z-10 material-symbols-outlined">shield</span>
                                    <span class="relative z-10">ACCÉDER AU PANEL</span>
                                    <span class="relative z-10 material-symbols-outlined transition-transform group-hover:translate-x-2">arrow_forward</span>
                                </a>
                            </div>

                            <div class="cyber-card p-8 rounded-xl animate-slide-in-right" style="animation-delay: 0.9s">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="flex items-center justify-center size-14 rounded-full bg-gradient-to-r from-primary to-accent">
                                        <span class="material-symbols-outlined text-white text-2xl">logout</span>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-display font-bold text-white">DÉCONNEXION</h3>
                                        <p class="text-text-sub">Quitter la session</p>
                                    </div>
                                </div>
                                <p class="text-text-sub mb-8">
                                    Terminez votre session d'administration en toute sécurité.
                                </p>
                                <a href="/ChopinSoft/index.php?route=user/logout"
                                   class="cyber-btn group relative flex items-center justify-center gap-3 w-full py-4 px-6 rounded-lg bg-gradient-to-r from-surface-dark to-surface-light border border-white/10 text-white font-display font-bold tracking-wider uppercase hover:border-primary/50 transition-all">
                                    <div class="absolute inset-0 bg-white/5 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                                    <span class="relative z-10 material-symbols-outlined">power_settings_new</span>
                                    <span class="relative z-10">SE DÉCONNECTER</span>
                                </a>
                            </div>

                        <?php else: ?>
                            <!-- Regular User Cards -->
                            <div class="cyber-card p-8 rounded-xl animate-slide-in-right" style="animation-delay: 0.7s">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="flex items-center justify-center size-14 rounded-full bg-gradient-to-r from-primary to-cyber-blue">
                                        <span class="material-symbols-outlined text-white text-2xl">person</span>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-display font-bold text-white">VOTRE PROFIL</h3>
                                        <p class="text-text-sub">Gérez votre avatar</p>
                                    </div>
                                </div>
                                <p class="text-text-sub mb-8">
                                    Personnalisez votre apparence et explorez vos statistiques.
                                </p>
                                <a href="/ChopinSoft/index.php?route=user/profile"
                                   class="cyber-btn group relative flex items-center justify-center gap-3 w-full py-4 px-6 rounded-lg bg-gradient-to-r from-primary to-cyber-blue text-white font-display font-bold tracking-wider uppercase shadow-neon-primary hover:shadow-[0_0_30px_rgba(0,243,255,0.6)] transition-all">
                                    <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                                    <span class="relative z-10 material-symbols-outlined">manage_accounts</span>
                                    <span class="relative z-10">MON PROFIL</span>
                                    <span class="relative z-10 material-symbols-outlined transition-transform group-hover:translate-x-2">arrow_forward</span>
                                </a>
                            </div>

                            <div class="cyber-card p-8 rounded-xl animate-slide-in-right" style="animation-delay: 0.9s">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="flex items-center justify-center size-14 rounded-full bg-gradient-to-r from-accent to-cyber-purple">
                                        <span class="material-symbols-outlined text-white text-2xl">play_arrow</span>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-display font-bold text-white">EXPLORER</h3>
                                        <p class="text-text-sub">Entrez dans le VR</p>
                                    </div>
                                </div>
                                <p class="text-text-sub mb-8">
                                    Plongez dans l'expérience immersive et explorez de nouveaux mondes.
                                </p>
                                <div class="space-y-4">
                                    <a href="/ChopinSoft/index.php?route=user/play"
                                       class="cyber-btn group relative flex items-center justify-center gap-3 w-full py-4 px-6 rounded-lg bg-gradient-to-r from-accent to-cyber-purple text-white font-display font-bold tracking-wider uppercase shadow-neon-accent hover:shadow-[0_0_30px_rgba(217,70,239,0.6)] transition-all">
                                        <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                                        <span class="relative z-10 material-symbols-outlined">rocket_launch</span>
                                        <span class="relative z-10">EXPLORER MAINTENANT</span>
                                        <span class="relative z-10 material-symbols-outlined transition-transform group-hover:translate-x-2">arrow_forward</span>
                                    </a>
                                    
                                    <a href="/ChopinSoft/index.php?route=user/logout"
                                       class="cyber-btn group relative flex items-center justify-center gap-3 w-full py-3 px-6 rounded-lg bg-surface-dark border border-white/10 text-text-sub font-display font-bold tracking-wider uppercase hover:text-white hover:border-primary/50 transition-all">
                                        <span class="material-symbols-outlined">logout</span>
                                        <span>SE DÉCONNECTER</span>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <!-- Visitor Card - Single centered card -->
                        <div class="cyber-card p-8 rounded-xl animate-slide-in-right md:col-span-2 max-w-md mx-auto" style="animation-delay: 0.7s">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="flex items-center justify-center size-14 rounded-full bg-gradient-to-r from-accent to-cyber-purple">
                                    <span class="material-symbols-outlined text-white text-2xl">login</span>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-display font-bold text-white">DÉJÀ MEMBRE</h3>
                                    <p class="text-text-sub">Reprenez votre voyage</p>
                                </div>
                            </div>
                            <p class="text-text-sub mb-8">
                                Reconnectez-vous à votre compte et continuez votre exploration.
                            </p>
                            <a href="/ChopinSoft/index.php?route=user/login"
                               class="cyber-btn group relative flex items-center justify-center gap-3 w-full py-4 px-6 rounded-lg bg-gradient-to-r from-accent to-cyber-purple text-white font-display font-bold tracking-wider uppercase shadow-neon-accent hover:shadow-[0_0_30px_rgba(217,70,239,0.6)] transition-all">
                                <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                                <span class="relative z-10 material-symbols-outlined">key</span>
                                <span class="relative z-10">SE CONNECTER</span>
                                <span class="relative z-10 material-symbols-outlined transition-transform group-hover:translate-x-2">arrow_forward</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Music control
        const music = document.getElementById('bg-music');
        const musicIcon = document.getElementById('music-icon');
        let isPlaying = false;
        
        function toggleMusic() {
            if (isPlaying) {
                music.pause();
                musicIcon.textContent = 'music_note';
            } else {
                music.play().catch(e => console.log("Audio autoplay prevented:", e));
                musicIcon.textContent = 'music_off';
            }
            isPlaying = !isPlaying;
        }
        
        // Try to autoplay music with user interaction
        document.addEventListener('click', function initAudio() {
            if (!isPlaying) {
                music.volume = 0.3;
                music.play().then(() => {
                    isPlaying = true;
                    musicIcon.textContent = 'music_off';
                }).catch(e => {
                    console.log("Audio autoplay prevented");
                });
            }
            document.removeEventListener('click', initAudio);
        });
        
        // Typing effect
        const typingText = document.getElementById('typing-text');
        const texts = [
            "SYSTEM READY",
            "BIENVENUE",
            "ENTER THE VR EXPERIENCE",
            "YOUR JOURNEY BEGINS NOW"
        ];
        let textIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        
        function typeWriter() {
            const currentText = texts[textIndex];
            
            if (isDeleting) {
                typingText.textContent = currentText.substring(0, charIndex - 1);
                charIndex--;
                
                if (charIndex === 0) {
                    isDeleting = false;
                    textIndex = (textIndex + 1) % texts.length;
                    setTimeout(typeWriter, 1000);
                } else {
                    setTimeout(typeWriter, 50);
                }
            } else {
                typingText.textContent = currentText.substring(0, charIndex + 1);
                charIndex++;
                
                if (charIndex === currentText.length) {
                    isDeleting = true;
                    setTimeout(typeWriter, 3000);
                } else {
                    setTimeout(typeWriter, 100);
                }
            }
        }
        
        // Start typing effect
        setTimeout(typeWriter, 1000);
        
        // Add hover effects to all cyber buttons
        document.querySelectorAll('.cyber-btn').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px) scale(1.02)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
        
        // Pulsing effect for join button
        const joinButton = document.querySelector('.join-btn');
        if (joinButton) {
            setInterval(() => {
                joinButton.style.animation = 'none';
                setTimeout(() => {
                    joinButton.style.animation = 'pulse-glow 2s infinite';
                }, 10);
            }, 4000);
        }
    </script>
</body>
</html>