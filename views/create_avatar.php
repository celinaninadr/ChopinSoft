<!DOCTYPE html>
<html>
<head>
    <title>Créer un avatar</title>
</head>
<body>
    <h2>Créer un nouvel avatar</h2>
    <form method="POST" action="index.php?action=create">
        <label>Nom : <input type="text" name="name" required></label><br><br>
        <label>Modèle 3D (URL ou nom) : <input type="text" name="model" required></label><br><br>
        <label>Image (optionnel) : <input type="text" name="img"></label><br><br>
        <button type="submit">Créer</button>
    </form>
    <br>
    <a href="login.php">Aller à la connexion</a>
</body>
</html>