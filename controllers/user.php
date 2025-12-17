<?php
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/world.php';
require_once __DIR__ . '/../models/avatar.php';

function userCreate(): void
{
    $worlds = worldAll();
    $avatars = avatarAll();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $idWorld = (int)($_POST['idWorld'] ?? 0);
        $idAvatar = (int)($_POST['idAvatar'] ?? 0);

        if ($username === '' || $password === '') {
            $_SESSION['flash'] = 'Username et password obligatoires.';
            redirectTo('user/create');
        }

        $existing = userDbFindByUsername($username);
        if ($existing) {
            $_SESSION['user'] = [
                'idUser' => (int)$existing['idUser'],
                'username' => $existing['username'],
                'userRole' => $existing['userRole'],
            ];
            $_SESSION['flash'] = 'Vous avez déjà un compte.';
            redirectTo('user/profile');
        }

        if ($idWorld <= 0) {
            $_SESSION['flash'] = 'Choisis un monde.';
            redirectTo('user/create');
        }
        if ($idAvatar <= 0) {
            $_SESSION['flash'] = 'Choisis un avatar.';
            redirectTo('user/create');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $idUser = userDbCreate($username, $hash, $idAvatar, $idWorld);

        $_SESSION['user'] = [
            'idUser' => $idUser,
            'username' => $username,
            'userRole' => 'JOUEUR',
        ];

        // On sauvegarde le choix courant pour "jouer"
        $_SESSION['play'] = [
            'idWorld' => $idWorld,
            'idAvatar' => $idAvatar,
        ];

        redirectTo('user/play');
    }

    render('user/create', [
        'worlds' => $worlds,
        'avatars' => $avatars,
    ]);
}

function userLogin(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = (string)($_POST['password'] ?? '');

        $u = userDbFindByUsername($username);
        if (!$u || !password_verify($password, $u['password'])) {
            $_SESSION['flash'] = 'Identifiants incorrects.';
            redirectTo('user/login');
        }

        if ($u['userRole'] === 'ADMIN') {
            $_SESSION['flash'] = 'Utilise le bouton "Login administrateur".';
            redirectTo('user/login');
        }

        $_SESSION['user'] = [
            'idUser' => (int)$u['idUser'],
            'username' => $u['username'],
            'userRole' => $u['userRole'],
        ];

        redirectTo('user/profile');
    }

    render('user/login');
}

function userProfile(): void
{
    requireLogin();

    $profile = userDbGetProfile((int)$_SESSION['user']['idUser']);
    $worlds = worldAll();
    $avatars = avatarAll();

    // pré-remplir pour "jouer"
    $_SESSION['play'] = [
        'idWorld' => (int)($profile['idWorld'] ?? 0),
        'idAvatar' => (int)($profile['idAvatar'] ?? 0),
    ];

    render('user/profile', [
        'profile' => $profile,
        'worlds' => $worlds,
        'avatars' => $avatars,
    ]);
}

function userChangeWorld(): void
{
    requireLogin();

    $idWorld = (int)($_POST['idWorld'] ?? 0);
    if ($idWorld > 0) {
        userDbUpdateWorld((int)$_SESSION['user']['idUser'], $idWorld);
        $_SESSION['play']['idWorld'] = $idWorld;
    }

    redirectTo('user/profile');
}

function userChangeAvatar(): void
{
    requireLogin();

    $idAvatar = (int)($_POST['idAvatar'] ?? 0);
    if ($idAvatar > 0) {
        userDbUpdateAvatar((int)$_SESSION['user']['idUser'], $idAvatar);
        $_SESSION['play']['idAvatar'] = $idAvatar;
    }

    redirectTo('user/profile');
}

function userPlay(): void
{
    requireLogin();

    $idWorld = (int)($_SESSION['play']['idWorld'] ?? 0);
    $idAvatar = (int)($_SESSION['play']['idAvatar'] ?? 0);

    $w = $idWorld > 0 ? worldFind($idWorld) : null;
    $a = $idAvatar > 0 ? avatarFind($idAvatar) : null;

    if (!$w || !$a) {
        $_SESSION['flash'] = 'Choisis un monde et un avatar.';
        redirectTo('user/profile');
    }

    // Lance le monde (portail)
    switch ($w['nameWorld']) {
        case 'desert':
            $url = 'worlds/desert.php';
            break;
        default:
            $_SESSION['flash'] = 'Monde non disponible.';
            redirectTo('user/profile');
    }
    $sep = (strpos($url, '?') === false) ? '?' : '&';

    // paramètres simples (si le portail les exploite)
    $url .= $sep . http_build_query([
        'username' => $_SESSION['user']['username'] ?? '',
        'idAvatar' => $a['idAvatar'],
        'modelAvatar' => $a['modelAvatar'],
    ]);

    header('Location: ' . $url);
    exit;
}

function userLogout(): void
{
    session_destroy();
    header('Location: /ChopinSoft/index.php?route=home/index');
    exit;
}
