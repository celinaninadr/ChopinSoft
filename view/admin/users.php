<!DOCTYPE html>
<html class="dark" lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Administration - Night City</title>
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
                    },
                    fontFamily: {
                        "display": ["Rajdhani", "sans-serif"],
                    },
                    borderRadius: {"DEFAULT": "0.3rem", "lg": "0.5rem", "xl": "1rem", "full": "9999px"},
                    boxShadow: {
                        "neon-primary": "0 0 15px rgba(0, 243, 255, 0.7), 0 0 30px rgba(0, 243, 255, 0.3)",
                        "neon-danger": "0 0 15px rgba(239, 68, 68, 0.7), 0 0 30px rgba(239, 68, 68, 0.3)",
                        "neon-warning": "0 0 15px rgba(245, 158, 11, 0.7), 0 0 30px rgba(245, 158, 11, 0.3)",
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s infinite',
                        'fade-in': 'fadeIn 0.8s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'glitch': 'glitch 0.5s infinite',
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
            background-position: right 1rem center;
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
        
        /* Admin table */
        .admin-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .admin-table th {
            background: linear-gradient(135deg, rgba(22, 27, 46, 0.9), rgba(16, 16, 26, 0.95));
            border-bottom: 2px solid rgba(0, 243, 255, 0.3);
            padding: 1rem;
            text-align: left;
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #00f3ff;
        }
        
        .admin-table td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.2);
        }
        
        .admin-table tr:hover td {
            background: rgba(0, 243, 255, 0.05);
        }
        
        .admin-table .user-form {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        
        /* Status badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .status-badge.joueur {
            background: rgba(0, 243, 255, 0.2);
            border: 1px solid rgba(0, 243, 255, 0.3);
            color: #00f3ff;
        }
        
        .status-badge.admin {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
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
    </style>
</head>
<body>
    <!-- Background elements -->
    <div class="cyber-bg"></div>

    <!-- Main content -->
    <div class="main-content min-h-screen p-4">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8 animate-fade-in">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-display font-black mb-2">
                            <span class="bg-gradient-to-r from-primary via-white to-accent bg-clip-text text-transparent">
                                ADMINISTRATION SYSTÈME
                            </span>
                        </h1>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                            <span class="text-sm text-text-sub font-display">SYSTEM CONTROL PANEL</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="/ChopinSoft/index.php?route=home/index" 
                           class="flex items-center gap-2 px-4 py-2 rounded-lg bg-surface-dark border border-white/10 text-text-sub hover:text-white hover:border-primary/50 transition-all">
                            <span class="material-symbols-outlined">home</span>
                            <span>Accueil</span>
                        </a>
                        <a href="/ChopinSoft/index.php?route=user/logout" 
                           class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-danger/20 to-warning/20 border border-danger/30 text-text-sub hover:text-white hover:border-danger/50 transition-all">
                            <span class="material-symbols-outlined">logout</span>
                            <span>Déconnexion</span>
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
                        <span class="text-xs text-text-sub font-display">admin@system:~</span>
                    </div>
                </div>
                
                <div class="flex gap-4">
                    <a href="/ChopinSoft/index.php?route=admin/worlds" 
                       class="cyber-btn flex-1 py-4 px-6 rounded-lg bg-gradient-to-r from-primary to-cyber-blue text-white font-display font-bold tracking-wider text-center shadow-neon-primary hover:shadow-[0_0_30px_rgba(0,243,255,0.6)] transition-all">
                        <span class="material-symbols-outlined align-middle mr-2">public</span>
                        GÉRER LES MONDES
                    </a>
                    
                    <a href="/ChopinSoft/index.php?route=admin/avatars" 
                       class="cyber-btn flex-1 py-4 px-6 rounded-lg bg-gradient-to-r from-accent to-cyber-purple text-white font-display font-bold tracking-wider text-center shadow-neon-accent hover:shadow-[0_0_30px_rgba(217,70,239,0.6)] transition-all">
                        <span class="material-symbols-outlined align-middle mr-2">person</span>
                        GÉRER LES AVATARS
                    </a>
                </div>
            </div>

            <!-- Add User Form -->
            <div class="cyber-card p-6 rounded-xl mb-8 animate-slide-up" style="animation-delay: 0.4s">
                <div class="section-header">
                    <h2 class="text-xl font-display font-bold text-white">AJOUTER UN UTILISATEUR</h2>
                </div>
                
                <form method="post">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-display font-bold uppercase tracking-wider text-primary mb-3">
                                USERNAME
                            </label>
                            <input type="text" 
                                   name="username" 
                                   required 
                                   class="cyber-input w-full"
                                   placeholder="Entrer username">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-display font-bold uppercase tracking-wider text-primary mb-3">
                                PASSWORD
                            </label>
                            <input type="password" 
                                   name="password" 
                                   required 
                                   class="cyber-input w-full"
                                   placeholder="••••••••">
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-display font-bold uppercase tracking-wider text-primary mb-3">
                                ROLE
                            </label>
                            <select name="userRole" required class="cyber-select w-full">
                                <option value="JOUEUR">JOUEUR</option>
                                <option value="ADMIN">ADMIN</option>
                            </select>
                        </div>
                        <div></div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-display font-bold uppercase tracking-wider text-primary mb-3">
                                AVATAR
                            </label>
                            <select name="idAvatar" required class="cyber-select w-full">
                                <option value="">-- choisir --</option>
                                <?php foreach ($avatars as $a): ?>
                                    <option value="<?php echo (int)$a['idAvatar']; ?>">
                                        <?php echo htmlspecialchars($a['nameAvatar']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-display font-bold uppercase tracking-wider text-primary mb-3">
                                MONDE
                            </label>
                            <select name="idWorld" required class="cyber-select w-full">
                                <option value="">-- choisir --</option>
                                <?php foreach ($worlds as $w): ?>
                                    <option value="<?php echo (int)$w['idWorld']; ?>">
                                        <?php echo htmlspecialchars($w['nameWorld']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" 
                            class="cyber-btn group relative flex items-center justify-center gap-3 w-full py-4 px-6 rounded-lg bg-gradient-to-r from-success to-cyber-blue text-white font-display font-bold tracking-wider uppercase shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:shadow-[0_0_30px_rgba(16,185,129,0.6)] transition-all">
                        <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <span class="relative z-10 material-symbols-outlined">add_circle</span>
                        <span class="relative z-10">CRÉER L'UTILISATEUR</span>
                        <span class="relative z-10 material-symbols-outlined transition-transform group-hover:translate-x-2">arrow_forward</span>
                    </button>
                </form>
            </div>

            <!-- Users Table -->
            <div class="cyber-card p-6 rounded-xl animate-slide-up" style="animation-delay: 0.6s">
                <div class="section-header">
                    <h2 class="text-xl font-display font-bold text-white">UTILISATEURS SYSTÈME</h2>
                    <div class="text-sm text-text-sub mt-1">
                        <?php echo count($users); ?> utilisateurs enregistrés
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>USERNAME</th>
                                <th>ROLE</th>
                                <th>AVATAR</th>
                                <th>MONDE</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td class="font-display font-bold">#<?php echo (int)$u['idUser']; ?></td>
                                    <td>
                                        <form method="post" class="user-form">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="idUser" value="<?php echo (int)$u['idUser']; ?>">
                                            <input type="text" 
                                                   name="username" 
                                                   value="<?php echo htmlspecialchars($u['username']); ?>" 
                                                   required 
                                                   class="cyber-input w-40">
                                    </td>
                                    <td>
                                        <select name="userRole" required class="cyber-select w-32">
                                            <option value="JOUEUR" <?php echo ($u['userRole'] === 'JOUEUR') ? 'selected' : ''; ?>>JOUEUR</option>
                                            <option value="ADMIN" <?php echo ($u['userRole'] === 'ADMIN') ? 'selected' : ''; ?>>ADMIN</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="idAvatar" required class="cyber-select w-40">
                                            <?php foreach ($avatars as $a): ?>
                                                <option value="<?php echo (int)$a['idAvatar']; ?>" <?php echo ((int)$a['idAvatar'] === (int)$u['idAvatar']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($a['nameAvatar']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="idWorld" required class="cyber-select w-40">
                                            <?php foreach ($worlds as $w): ?>
                                                <option value="<?php echo (int)$w['idWorld']; ?>" <?php echo ((int)$w['idWorld'] === (int)$u['idWorld']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($w['nameWorld']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="space-y-2">
                                            <div class="text-xs text-text-sub mb-1">Nouveau password (optionnel):</div>
                                            <input type="password" 
                                                   name="newPassword" 
                                                   placeholder="••••••••" 
                                                   class="cyber-input w-full mb-2">
                                            
                                            <div class="flex gap-2">
                                                <button type="submit" 
                                                        class="cyber-btn flex-1 py-2 px-4 rounded-lg bg-gradient-to-r from-warning to-cyber-purple text-white font-display font-bold text-sm tracking-wider uppercase shadow-neon-warning hover:shadow-[0_0_20px_rgba(245,158,11,0.6)] transition-all">
                                                    Modifier
                                                </button>
                                                </form>
                                                
                                                <form method="post" 
                                                      onsubmit="return confirmDelete()"
                                                      class="flex-1">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="idUser" value="<?php echo (int)$u['idUser']; ?>">
                                                    <button type="submit" 
                                                            class="cyber-btn w-full py-2 px-4 rounded-lg bg-gradient-to-r from-danger to-cyber-purple text-white font-display font-bold text-sm tracking-wider uppercase shadow-neon-danger hover:shadow-[0_0_20px_rgba(239,68,68,0.6)] transition-all">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
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
        
        // Delete confirmation
        function confirmDelete() {
            return confirm('⚠️ Confirmer la suppression ? Cette action est irréversible.');
        }
        
        // Form submission animations
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    const originalText = submitBtn.innerHTML;
                    
                    if (this.querySelector('input[name="action"]').value === 'delete') {
                        submitBtn.innerHTML = `
                            <span class="material-symbols-outlined animate-spin">delete</span>
                            <span class="ml-2">SUPPRESSION...</span>
                        `;
                    } else if (this.querySelector('input[name="action"]').value === 'update') {
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
        
        // Add glitch effect to admin title randomly
        const adminTitle = document.querySelector('h1');
        if (adminTitle) {
            setInterval(() => {
                if (Math.random() > 0.9) {
                    adminTitle.style.animation = 'none';
                    setTimeout(() => {
                        adminTitle.style.animation = 'glitch 0.3s';
                        setTimeout(() => {
                            adminTitle.style.animation = '';
                        }, 300);
                    }, 10);
                }
            }, 5000);
        }
        
        // Add hover effects to table rows
        document.querySelectorAll('.admin-table tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(5px)';
                this.style.transition = 'transform 0.3s ease';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
        
        // Highlight admin users
        document.querySelectorAll('select[name="userRole"]').forEach(select => {
            select.addEventListener('change', function() {
                const row = this.closest('tr');
                if (this.value === 'ADMIN') {
                    row.style.background = 'rgba(239, 68, 68, 0.1)';
                    row.style.borderLeft = '3px solid #ef4444';
                } else {
                    row.style.background = '';
                    row.style.borderLeft = '';
                }
            });
            
            // Initial highlight
            if (select.value === 'ADMIN') {
                const row = select.closest('tr');
                row.style.background = 'rgba(239, 68, 68, 0.1)';
                row.style.borderLeft = '3px solid #ef4444';
            }
        });
        
        // Real-time user count update
        const userCount = document.querySelector('.text-text-sub');
        if (userCount) {
            const originalCount = <?php echo count($users); ?>;
            let fakeCount = originalCount;
            
            setInterval(() => {
                if (Math.random() > 0.7 && fakeCount < originalCount + 5) {
                    fakeCount++;
                    userCount.textContent = `${fakeCount} utilisateurs enregistrés`;
                    
                    // Flash effect
                    userCount.style.color = '#00f3ff';
                    setTimeout(() => {
                        userCount.style.color = '#94a3b8';
                    }, 500);
                }
            }, 10000);
        }
    </script>
</body>
</html>