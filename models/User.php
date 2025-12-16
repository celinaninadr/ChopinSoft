<?php
require_once __DIR__ . '/../app/pdo.php';

function userDbFindByUsername(string $username): ?array
{
    $stmt = getDb()->prepare('SELECT * FROM `user` WHERE username = ?');
    $stmt->execute([$username]);
    $u = $stmt->fetch();
    return $u ?: null;
}

function userDbFindById(int $idUser): ?array
{
    $stmt = getDb()->prepare('SELECT * FROM `user` WHERE idUser = ?');
    $stmt->execute([$idUser]);
    $u = $stmt->fetch();
    return $u ?: null;
}

function userDbCreate(string $username, string $hash, int $idAvatar, int $idWorld): int
{
    $stmt = getDb()->prepare("INSERT INTO `user` (username, password, userRole, idAvatar, idWorld) VALUES (?, ?, 'JOUEUR', ?, ?)");
    $stmt->execute([$username, $hash, $idAvatar, $idWorld]);
    return (int)getDb()->lastInsertId();
}

function adminDbUserCreate(string $username, string $hash, string $role, int $idAvatar, int $idWorld): int
{
    $stmt = getDb()->prepare('INSERT INTO `user` (username, password, userRole, idAvatar, idWorld) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$username, $hash, $role, $idAvatar, $idWorld]);
    return (int)getDb()->lastInsertId();
}

function userDbUpdateWorld(int $idUser, int $idWorld): void
{
    $stmt = getDb()->prepare('UPDATE `user` SET idWorld = ? WHERE idUser = ?');
    $stmt->execute([$idWorld, $idUser]);
}

function userDbUpdateAvatar(int $idUser, int $idAvatar): void
{
    $stmt = getDb()->prepare('UPDATE `user` SET idAvatar = ? WHERE idUser = ?');
    $stmt->execute([$idAvatar, $idUser]);
}

function adminDbUserUpdate(int $idUser, string $username, string $role, int $idAvatar, int $idWorld): void
{
    $stmt = getDb()->prepare('UPDATE `user` SET username = ?, userRole = ?, idAvatar = ?, idWorld = ? WHERE idUser = ?');
    $stmt->execute([$username, $role, $idAvatar, $idWorld, $idUser]);
}

function adminDbUserUpdatePassword(int $idUser, string $hash): void
{
    $stmt = getDb()->prepare('UPDATE `user` SET password = ? WHERE idUser = ?');
    $stmt->execute([$hash, $idUser]);
}

function adminDbUserDelete(int $idUser): void
{
    $stmt = getDb()->prepare('DELETE FROM `user` WHERE idUser = ?');
    $stmt->execute([$idUser]);
}

function userDbGetProfile(int $idUser): ?array
{
    $sql = "SELECT u.idUser, u.username, u.userRole, u.idWorld, a.idAvatar, a.nameAvatar, a.modelAvatar, a.imgAvatar
            FROM `user` u
            JOIN avatar a ON a.idAvatar = u.idAvatar
            WHERE u.idUser = ?";
    $stmt = getDb()->prepare($sql);
    $stmt->execute([$idUser]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function adminDbUsersList(): array
{
    $sql = "SELECT u.idUser, u.username, u.userRole, u.idAvatar, u.idWorld, a.nameAvatar, w.nameWorld
            FROM `user` u
            JOIN avatar a ON a.idAvatar = u.idAvatar
            JOIN world w ON w.idWorld = u.idWorld
            ORDER BY u.idUser DESC";
    return getDb()->query($sql)->fetchAll();
}
