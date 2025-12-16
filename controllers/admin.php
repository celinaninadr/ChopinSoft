<?php
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/world.php';
require_once __DIR__ . '/../models/avatar.php';

function adminLogin(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = (string)($_POST['password'] ?? '');

        $u = userDbFindByUsername($username);
        if (!$u || !password_verify($password, $u['password'])) {
            setFlash('Identifiants incorrects.');
            redirectTo('admin/login');
        }

        if ($u['userRole'] !== 'ADMIN') {
            setFlash('Vous n\'êtes pas administrateur.');
            redirectTo('admin/login');
        }

        $_SESSION['user'] = [
            'idUser' => (int)$u['idUser'],
            'username' => $u['username'],
            'userRole' => $u['userRole'],
        ];

        redirectTo('admin/users');
    }

    render('admin/login');
}

function adminUsers(): void
{
    requireAdmin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        if ($action === 'create') {
            $username = trim($_POST['username'] ?? '');
            $password = (string)($_POST['password'] ?? '');
            $role = trim($_POST['userRole'] ?? 'JOUEUR');
            $idAvatar = (int)($_POST['idAvatar'] ?? 0);
            $idWorld = (int)($_POST['idWorld'] ?? 0);

            if ($username === '' || $password === '' || $idAvatar <= 0 || $idWorld <= 0) {
                setFlash('Champs manquants pour créer l\'utilisateur.');
                redirectTo('admin/users');
            }
            if ($role !== 'ADMIN' && $role !== 'JOUEUR') {
                $role = 'JOUEUR';
            }
            if (userDbFindByUsername($username)) {
                setFlash('Username déjà utilisé.');
                redirectTo('admin/users');
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);
            adminDbUserCreate($username, $hash, $role, $idAvatar, $idWorld);
            setFlash('Utilisateur créé.');
            redirectTo('admin/users');
        }

        if ($action === 'update') {
            $idUser = (int)($_POST['idUser'] ?? 0);
            $username = trim($_POST['username'] ?? '');
            $role = trim($_POST['userRole'] ?? 'JOUEUR');
            $idAvatar = (int)($_POST['idAvatar'] ?? 0);
            $idWorld = (int)($_POST['idWorld'] ?? 0);
            $newPassword = (string)($_POST['newPassword'] ?? '');

            if ($idUser <= 0 || $username === '' || $idAvatar <= 0 || $idWorld <= 0) {
                setFlash('Champs manquants pour modifier l\'utilisateur.');
                redirectTo('admin/users');
            }
            if ($role !== 'ADMIN' && $role !== 'JOUEUR') {
                $role = 'JOUEUR';
            }

            // éviter collision username sur un autre user
            $existing = userDbFindByUsername($username);
            if ($existing && (int)$existing['idUser'] !== $idUser) {
                setFlash('Username déjà utilisé par un autre utilisateur.');
                redirectTo('admin/users');
            }

            adminDbUserUpdate($idUser, $username, $role, $idAvatar, $idWorld);
            if ($newPassword !== '') {
                $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                adminDbUserUpdatePassword($idUser, $hash);
            }

            setFlash('Utilisateur modifié.');
            redirectTo('admin/users');
        }

        if ($action === 'delete') {
            $idUser = (int)($_POST['idUser'] ?? 0);
            if ($idUser > 0) {
                adminDbUserDelete($idUser);
                setFlash('Utilisateur supprimé.');
            }
            redirectTo('admin/users');
        }
    }

    $users = adminDbUsersList();
    $avatars = avatarAll();
    $worlds = worldAll();
    render('admin/users', [
        'users' => $users,
        'avatars' => $avatars,
        'worlds' => $worlds,
    ]);
}

function adminWorlds(): void
{
    requireAdmin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        if ($action === 'create') {
            $name = trim($_POST['nameWorld'] ?? '');
            $img = trim($_POST['imgWorld'] ?? '');
            $url = trim($_POST['urlWorld'] ?? '');
            if ($name !== '' && $url !== '') {
                worldCreate($name, $img !== '' ? $img : null, $url);
            }
        }

        if ($action === 'update') {
            $id = (int)($_POST['idWorld'] ?? 0);
            $name = trim($_POST['nameWorld'] ?? '');
            $img = trim($_POST['imgWorld'] ?? '');
            $url = trim($_POST['urlWorld'] ?? '');
            if ($id > 0 && $name !== '' && $url !== '') {
                worldUpdate($id, $name, $img !== '' ? $img : null, $url);
            }
        }

        if ($action === 'delete') {
            $id = (int)($_POST['idWorld'] ?? 0);
            if ($id > 0) {
                if (worldIsUsed($id)) {
                    setFlash('Impossible de supprimer ce monde : il est utilisé par un utilisateur.');
                } else {
                    worldDelete($id);
                }
            }
        }

        redirectTo('admin/worlds');
    }

    $worlds = worldAll();
    render('admin/worlds', ['worlds' => $worlds]);
}

function adminAvatars(): void
{
    requireAdmin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        if ($action === 'create') {
            $name = trim($_POST['nameAvatar'] ?? '');
            $model = trim($_POST['modelAvatar'] ?? '');
            $img = trim($_POST['imgAvatar'] ?? '');
            if ($name !== '' && $model !== '') {
                avatarCreate($name, $model, $img !== '' ? $img : null);
            }
        }

        if ($action === 'update') {
            $id = (int)($_POST['idAvatar'] ?? 0);
            $name = trim($_POST['nameAvatar'] ?? '');
            $model = trim($_POST['modelAvatar'] ?? '');
            $img = trim($_POST['imgAvatar'] ?? '');
            if ($id > 0 && $name !== '' && $model !== '') {
                avatarUpdate($id, $name, $model, $img !== '' ? $img : null);
            }
        }

        if ($action === 'delete') {
            $id = (int)($_POST['idAvatar'] ?? 0);
            if ($id > 0) {
                if (avatarIsUsed($id)) {
                    setFlash('Impossible de supprimer cet avatar : il est utilisé par un utilisateur.');
                } else {
                    avatarDelete($id);
                }
            }
        }

        redirectTo('admin/avatars');
    }

    $avatars = avatarAll();
    render('admin/avatars', ['avatars' => $avatars]);
}
