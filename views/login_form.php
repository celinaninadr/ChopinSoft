<!DOCTYPE html>
<html>
<head>
    <title>Connexion & Sélection</title>
</head>
<body>
    <h2>Connexion + Choix d'avatar et de monde</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php?action=login">
        <label>Username : <input type="text" name="username" required></label><br><br>
        <label>Mot de passe : <input type="password" name="password" required></label><br><br>

        <label>Avatar :
            <select name="idAvatar" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($avatars as $a): ?>
                    <option value="<?= $a['idAvatar'] ?>"><?= htmlspecialchars($a['nameAvatar']) ?></option>
                <?php endforeach; ?>
            </select>
        </label><br><br>

        <label>Monde :
            <select name="idWorld" required>
                <option value="">-- Choisir --</option>
                <?php foreach ($worlds as $w): ?>
                    <option value="<?= $w['idWorld'] ?>"><?= htmlspecialchars($w['nameWorld']) ?></option>
                <?php endforeach; ?>
            </select>
        </label><br><br>

        <button type="submit">Se connecter</button>
    </form>

    <br>
    <a href="index.php">Créer un avatar</a>
</body>
</html>