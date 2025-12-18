<?php
require_once __DIR__ . '/../app/pdo.php';

function userDbFindByUsername(string $username): ?array
{
    try {
        $stmt = getDb()->prepare('SELECT * FROM `user` WHERE username = :username');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $u = $stmt->fetch();
        return $u ?: null;
    } catch (PDOException $e) {
        error_log('Erreur base de données dans userDbFindByUsername: ' . $e->getMessage());
        return null;
    }
}

function userDbFindById(int $idUser): ?array
{
    try {
        $stmt = getDb()->prepare('SELECT * FROM `user` WHERE idUser = :idUser');
        $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $stmt->execute();
        $u = $stmt->fetch();
        return $u ?: null;
    } catch (PDOException $e) {
        error_log('Erreur base de données dans userDbFindById: ' . $e->getMessage());
        return null;
    }
}

function userDbCreate(string $username, string $hash, int $idAvatar, int $idWorld): int
{
    try {
        $role = 'JOUEUR';
        $stmt = getDb()->prepare('INSERT INTO `user` (username, password, userRole, idAvatar, idWorld) VALUES (:username, :password, :userRole, :idAvatar, :idWorld)');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hash, PDO::PARAM_STR);
        $stmt->bindParam(':userRole', $role, PDO::PARAM_STR);
        $stmt->bindParam(':idAvatar', $idAvatar, PDO::PARAM_INT);
        $stmt->bindParam(':idWorld', $idWorld, PDO::PARAM_INT);
        $stmt->execute();
        return (int)getDb()->lastInsertId();
    } catch (PDOException $e) {
        error_log('Erreur base de données dans userDbCreate: ' . $e->getMessage());
        return 0;
    }
}

function adminDbUserCreate(string $username, string $hash, string $role, int $idAvatar, int $idWorld): int
{
    try {
        $stmt = getDb()->prepare('INSERT INTO `user` (username, password, userRole, idAvatar, idWorld) VALUES (:username, :password, :userRole, :idAvatar, :idWorld)');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hash, PDO::PARAM_STR);
        $stmt->bindParam(':userRole', $role, PDO::PARAM_STR);
        $stmt->bindParam(':idAvatar', $idAvatar, PDO::PARAM_INT);
        $stmt->bindParam(':idWorld', $idWorld, PDO::PARAM_INT);
        $stmt->execute();
        return (int)getDb()->lastInsertId();
    } catch (PDOException $e) {
        error_log('Erreur base de données dans adminDbUserCreate: ' . $e->getMessage());
        return 0;
    }
}

function userDbUpdateWorld(int $idUser, int $idWorld): void
{
    try {
        $stmt = getDb()->prepare('UPDATE `user` SET idWorld = :idWorld WHERE idUser = :idUser');
        $stmt->bindParam(':idWorld', $idWorld, PDO::PARAM_INT);
        $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log('Erreur base de données dans userDbUpdateWorld: ' . $e->getMessage());
    }
}

function userDbUpdateAvatar(int $idUser, int $idAvatar): void
{
    try {
        $stmt = getDb()->prepare('UPDATE `user` SET idAvatar = :idAvatar WHERE idUser = :idUser');
        $stmt->bindParam(':idAvatar', $idAvatar, PDO::PARAM_INT);
        $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log('Erreur base de données dans userDbUpdateAvatar: ' . $e->getMessage());
    }
}

function adminDbUserUpdate(int $idUser, string $username, string $role, int $idAvatar, int $idWorld): void
{
    try {
        $stmt = getDb()->prepare('UPDATE `user` SET username = :username, userRole = :userRole, idAvatar = :idAvatar, idWorld = :idWorld WHERE idUser = :idUser');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':userRole', $role, PDO::PARAM_STR);
        $stmt->bindParam(':idAvatar', $idAvatar, PDO::PARAM_INT);
        $stmt->bindParam(':idWorld', $idWorld, PDO::PARAM_INT);
        $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log('Erreur base de données dans adminDbUserUpdate: ' . $e->getMessage());
    }
}

function adminDbUserUpdatePassword(int $idUser, string $hash): void
{
    try {
        $stmt = getDb()->prepare('UPDATE `user` SET password = :password WHERE idUser = :idUser');
        $stmt->bindParam(':password', $hash, PDO::PARAM_STR);
        $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log('Erreur base de données dans adminDbUserUpdatePassword: ' . $e->getMessage());
    }
}

function adminDbUserDelete(int $idUser): void
{
    try {
        $stmt = getDb()->prepare('DELETE FROM `user` WHERE idUser = :idUser');
        $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log('Erreur base de données dans adminDbUserDelete: ' . $e->getMessage());
    }
}

function userDbGetProfile(int $idUser): ?array
{
    try {
        $sql = "SELECT u.idUser, u.username, u.userRole, u.idWorld, a.idAvatar, a.nameAvatar, a.modelAvatar, a.imgAvatar
                FROM `user` u
                LEFT JOIN avatar a ON a.idAvatar = u.idAvatar
                WHERE u.idUser = :idUser";
        $stmt = getDb()->prepare($sql);
        $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ?: null;
    } catch (PDOException $e) {
        error_log('Erreur base de données dans userDbGetProfile: ' . $e->getMessage());
        return null;
    }
}

function adminDbUsersList(): array
{
    try {
        $sql = "SELECT u.idUser, u.username, u.userRole, u.idAvatar, u.idWorld, a.nameAvatar, w.nameWorld
                FROM `user` u
                JOIN avatar a ON a.idAvatar = u.idAvatar
                JOIN world w ON w.idWorld = u.idWorld
                ORDER BY u.idUser DESC";
        $stmt = getDb()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log('Erreur base de données dans adminDbUsersList: ' . $e->getMessage());
        return [];
    }
}
