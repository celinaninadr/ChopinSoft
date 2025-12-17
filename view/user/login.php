<!DOCTYPE html>
<html class="dark" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Connexion - Night City</title>
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
        
        .input-container {
            position: relative;
        }
        
        .input-container::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #00f3ff, #d946ef);
            transition: width 0.3s ease;
        }
        
        .input-container:focus-within::after {
            width: 100%;
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
        
        /* Status indicator */
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
            background: #ef4444;
            animation: pulse 2s infinite;
        }
        
        .status-indicator.connected::after {
            background: #10b981;
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
        
        /* Password toggle */
        .password-toggle {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0.25rem;
            transition: color 0.3s;
        }
        
        .password-toggle:hover {
            color: #00f3ff;
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
    </style>
</head>
<body>
    <!-- Background elements -->
    <div class="cyber-bg"></div>
    <div class="glow-effect"></div>
    <div class="scanlines"></div>

    <!-- Main content -->
    <div class="main-content min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Header -->
            <div class="text-center mb-8 animate-fade-in">
                <div class="flex justify-center mb-6">
                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-primary/20 to-accent/20 border border-primary/30 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-3xl">key</span>
                    </div>
                </div>
                
                <h1 class="text-3xl font-display font-black mb-2">
                    <span class="bg-gradient-to-r from-primary via-white to-accent bg-clip-text text-transparent">
                        ACCÈS SYSTÈME
                    </span>
                </h1>
                
                <div class="terminal-text text-sm mb-6">
                    <span class="text-primary">system@nightcity:~$</span>
                    <span class="ml-2 text-white">login --secure</span>
                </div>
                
                <div class="status-indicator connected inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-dark border border-white/10">
                    <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                    <span class="text-xs font-display text-text-sub">SYSTEM: ONLINE</span>
                </div>
            </div>

            <!-- Login Form -->
            <form method="post" class="cyber-card p-8 rounded-xl animate-slide-up" style="animation-delay: 0.2s">
                <!-- Terminal header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                        <span class="text-xs text-text-sub font-display">login_terminal</span>
                    </div>
                    <div class="h-px bg-gradient-to-r from-primary/50 via-accent/50 to-primary/50"></div>
                </div>

                <!-- Username field -->
                <div class="mb-6">
                    <label class="cyber-label">
                        USERNAME
                    </label>
                    <div class="input-container">
                        <input 
                            type="text" 
                            name="username" 
                            required 
                            class="cyber-input w-full"
                            placeholder="ENTRER USERNAME"
                            autocomplete="username"
                        />
                    </div>
                    <div class="text-xs text-text-sub mt-2">
                        Votre identifiant dans le réseau
                    </div>
                </div>

                <!-- Password field -->
                <div class="mb-8">
                    <label class="cyber-label">
                        PASSWORD
                    </label>
                    <div class="input-container relative">
                        <input 
                            type="password" 
                            name="password" 
                            required 
                            class="cyber-input w-full pr-10"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            id="password-input"
                        />
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <span class="material-symbols-outlined text-sm" id="toggle-icon">visibility</span>
                        </button>
                    </div>
                    <div class="text-xs text-text-sub mt-2">
                        Code d'accès crypté requis
                    </div>
                </div>

                <!-- Security check -->
                <div class="mb-8 p-4 bg-black/30 rounded border border-white/10">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-display text-text-sub">NIVEAU DE SÉCURITÉ</span>
                        <span class="text-sm font-display text-primary font-bold">MAXIMUM</span>
                    </div>
                    <div class="w-full bg-white/10 h-1.5 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-primary to-accent w-full"></div>
                    </div>
                </div>

                <!-- Submit button -->
                <button 
                    type="submit" 
                    class="cyber-btn group relative flex items-center justify-center gap-3 w-full py-4 px-6 rounded-lg bg-gradient-to-r from-primary via-accent to-cyber-purple text-white font-display font-bold tracking-wider uppercase shadow-neon-primary hover:shadow-[0_0_40px_rgba(0,243,255,0.7)] hover:scale-105 transition-all">
                    <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                    <span class="relative z-10 material-symbols-outlined">login</span>
                    <span class="relative z-10">INITIALISER LA CONNEXION</span>
                    <span class="relative z-10 material-symbols-outlined transition-transform group-hover:translate-x-2">arrow_forward</span>
                </button>

                <!-- Back link -->
                <div class="mt-6 text-center">
                    <a href="/ChopinSoft/index.php?route=home/index" 
                       class="inline-flex items-center gap-2 text-sm text-text-sub hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-sm">arrow_back</span>
                        Retour à l'accueil
                    </a>
                </div>
            </form>

            <!-- System messages -->
            <div class="mt-8 text-center animate-slide-up" style="animation-delay: 0.4s">
                <div class="cyber-card p-4 rounded-lg">
                    <div class="flex items-center justify-center gap-3">
                        <span class="material-symbols-outlined text-primary">info</span>
                        <div class="text-left">
                            <div class="text-xs font-display text-text-sub uppercase">NOUVEAU SUR NIGHT CITY?</div>
                            <a href="/ChopinSoft/index.php?route=user/create" 
                               class="text-sm text-primary hover:text-accent transition-colors">
                                Créez votre identité virtuelle →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password-input');
            const toggleIcon = document.getElementById('toggle-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = 'visibility_off';
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = 'visibility';
            }
        }
        
        // Ripple effect on buttons
        document.querySelectorAll('button, a').forEach(element => {
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
        
        // Form submission animation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = `
                <span class="material-symbols-outlined animate-spin">refresh</span>
                <span class="ml-2">CONNEXION EN COURS...</span>
            `;
            submitBtn.disabled = true;
            
            // Revert after 3 seconds if still on page
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });
        
        // Add glitch effect randomly
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
        
        // Add typing effect to inputs on focus
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-primary/30');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-primary/30');
            });
        });
        
        // Simulate system check
        setTimeout(() => {
            const status = document.querySelector('.status-indicator');
            if (status) {
                status.style.animation = 'none';
                setTimeout(() => {
                    status.style.animation = 'pulse 2s infinite';
                }, 10);
            }
        }, 1000);
    </script>
</body>
</html>