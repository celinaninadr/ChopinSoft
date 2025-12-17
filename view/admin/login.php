<!DOCTYPE html>
<html class="dark" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Admin Login - VR System</title>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
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
                    boxShadow: {
                        "neon-primary": "0 0 15px rgba(0, 243, 255, 0.7), 0 0 30px rgba(0, 243, 255, 0.3)",
                        "neon-accent": "0 0 15px rgba(217, 70, 239, 0.7), 0 0 30px rgba(217, 70, 239, 0.3)",
                        "neon-danger": "0 0 15px rgba(239, 68, 68, 0.7), 0 0 30px rgba(239, 68, 68, 0.3)",
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.8s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'neon-pulse': 'neonPulse 2s infinite',
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
                        neonPulse: {
                            '0%, 100%': { opacity: '1' },
                            '50%': { opacity: '0.7' }
                        },
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
        
        .cyber-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(239, 68, 68, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(239, 68, 68, 0.03) 1px, transparent 1px);
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
            background: radial-gradient(circle, rgba(239, 68, 68, 0.1) 0%, transparent 70%);
            filter: blur(100px);
            pointer-events: none;
            z-index: 0;
        }
        
        .main-content {
            position: relative;
            z-index: 10;
        }
        
        .terminal-window {
            background: rgba(5, 5, 10, 0.95);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 0.5rem;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }
        
        .terminal-header {
            background: linear-gradient(90deg, #0b0b15, #161b2e);
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(239, 68, 68, 0.2);
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
        
        .cyber-input {
            background: rgba(5, 5, 10, 0.8);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 0.5rem;
            padding: 1rem 1.25rem;
            color: white;
            font-family: 'Rajdhani', sans-serif;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
        }
        
        .cyber-input:focus {
            outline: none;
            border-color: rgba(239, 68, 68, 0.7);
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.3);
        }
        
        .cyber-input::placeholder {
            color: #64748b;
        }
        
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
        
        .typing-cursor {
            display: inline-block;
            width: 8px;
            height: 1em;
            background-color: #ef4444;
            margin-left: 4px;
            animation: blink 1s infinite;
            vertical-align: middle;
        }
        
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
        
        .scan-line {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(239, 68, 68, 0.5), transparent);
            animation: scan 3s linear infinite;
        }
        
        @keyframes scan {
            0% { top: 0; }
            100% { top: 100%; }
        }
    </style>
</head>
<body>
    <div class="cyber-bg"></div>
    <div class="glow-effect"></div>

    <div class="main-content min-h-screen flex flex-col items-center justify-center py-8 px-4">
        <div class="w-full max-w-md">
            
            <div class="text-center mb-8 animate-fade-in">
                <div class="inline-flex items-center justify-center size-20 rounded-full bg-gradient-to-r from-danger to-cyber-purple mb-6 shadow-neon-danger">
                    <span class="material-symbols-outlined text-white text-4xl">shield</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-display font-black">
                    <span class="bg-gradient-to-r from-danger via-white to-cyber-purple bg-clip-text text-transparent">
                        ACCÈS ADMINISTRATEUR
                    </span>
                </h1>
                <p class="text-text-sub mt-2 font-display">Zone sécurisée - Authentification requise</p>
            </div>

            <div class="terminal-window animate-slide-up" style="animation-delay: 0.2s">
                <div class="terminal-header">
                    <div class="flex justify-between items-center">
                        <div class="terminal-dots">
                            <div class="terminal-dot red"></div>
                            <div class="terminal-dot yellow"></div>
                            <div class="terminal-dot green"></div>
                        </div>
                        <span class="text-xs text-text-sub font-display">admin@secure:~</span>
                    </div>
                </div>
                
                <div class="p-6 relative">
                    <div class="scan-line"></div>
                    
                    <div class="mb-6 font-mono text-sm">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-danger">>></span>
                            <span class="text-white" id="typing-text"></span>
                            <span class="typing-cursor"></span>
                        </div>
                        <p class="text-danger/70">⚠ Connexion sécurisée requise</p>
                    </div>

                    <form method="post" class="space-y-5">
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-display font-bold text-text-sub uppercase tracking-wider">
                                Identifiant
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="username" 
                                    required
                                    class="cyber-input"
                                    placeholder="Entrez votre identifiant"
                                    autocomplete="username"
                                >
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-display font-bold text-text-sub uppercase tracking-wider">
                                Mot de passe
                            </label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    name="password" 
                                    required
                                    class="cyber-input"
                                    placeholder="••••••••••••"
                                    autocomplete="current-password"
                                >
                            </div>
                        </div>

                        <button 
                            type="submit"
                            class="cyber-btn group relative flex items-center justify-center gap-3 w-full py-4 px-6 rounded-lg bg-gradient-to-r from-danger to-cyber-purple text-white font-display font-bold text-lg tracking-wider uppercase shadow-neon-danger hover:shadow-[0_0_30px_rgba(239,68,68,0.6)] transition-all mt-8"
                        >
                            <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300 rounded-lg"></div>
                            <span class="relative z-10 material-symbols-outlined">login</span>
                            <span class="relative z-10">CONNEXION SÉCURISÉE</span>
                            <span class="relative z-10 material-symbols-outlined transition-transform group-hover:translate-x-2">arrow_forward</span>
                        </button>
                    </form>

                    <div class="mt-6 pt-4 border-t border-white/10 text-center">
                        <p class="text-xs text-text-sub font-mono">
                            <span class="text-danger">●</span> Connexion chiffrée SSL/TLS
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-6 animate-slide-up" style="animation-delay: 0.4s">
                <a href="/ChopinSoft/index.php" class="inline-flex items-center gap-2 text-text-sub hover:text-primary transition-colors font-display">
                    <span class="material-symbols-outlined">arrow_back</span>
                    <span>Retour à l'accueil</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Typing effect
        const typingText = document.getElementById('typing-text');
        const texts = [
            "INITIALIZING SECURE CONNECTION...",
            "ADMIN AUTHENTICATION REQUIRED",
            "AWAITING CREDENTIALS...",
            "SECURITY LEVEL: MAXIMUM"
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
                    setTimeout(typeWriter, 800);
                } else {
                    setTimeout(typeWriter, 40);
                }
            } else {
                typingText.textContent = currentText.substring(0, charIndex + 1);
                charIndex++;
                
                if (charIndex === currentText.length) {
                    isDeleting = true;
                    setTimeout(typeWriter, 2500);
                } else {
                    setTimeout(typeWriter, 80);
                }
            }
        }
        
        setTimeout(typeWriter, 500);
        
        // Input focus effects
        document.querySelectorAll('.cyber-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
    </script>
</body>
</html>