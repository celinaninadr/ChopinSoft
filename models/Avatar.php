<?php
require_once __DIR__ . '/../app/pdo.php';

function avatarAll(): array
{
    try {
        $sql = 'SELECT * FROM avatar ORDER BY idAvatar ASC';
        $stmt = getDb()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log('Database error in avatarAll: ' . $e->getMessage());
        return [];
    }
}

function avatarIsUsed(int $idAvatar): bool
{
    try {
        $stmt = getDb()->prepare('SELECT COUNT(*) AS cnt FROM `user` WHERE idAvatar = :idAvatar');
        $stmt->bindParam(':idAvatar', $idAvatar, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        $count = isset($row['cnt']) ? $row['cnt'] : 0;
        return (int)$count > 0;
    } catch (PDOException $e) {
        error_log('Database error in avatarIsUsed: ' . $e->getMessage());
        return false;
    }
}

function avatarFind(int $idAvatar): ?array
{
    try {
        $stmt = getDb()->prepare('SELECT * FROM avatar WHERE idAvatar = :idAvatar');
        $stmt->bindParam(':idAvatar', $idAvatar, PDO::PARAM_INT);
        $stmt->execute();
        $a = $stmt->fetch();
        return $a ?: null;
    } catch (PDOException $e) {
        error_log('Database error in avatarFind: ' . $e->getMessage());
        return null;
    }
}

function avatarCreate(string $name, string $model, ?string $img): void
{
    try {
        $stmt = getDb()->prepare('INSERT INTO avatar (nameAvatar, modelAvatar, imgAvatar) VALUES (:nameAvatar, :modelAvatar, :imgAvatar)');
        $stmt->bindParam(':nameAvatar', $name, PDO::PARAM_STR);
        $stmt->bindParam(':modelAvatar', $model, PDO::PARAM_STR);
        $stmt->bindParam(':imgAvatar', $img, PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log('Database error in avatarCreate: ' . $e->getMessage());
    }
}

function avatarUpdate(int $id, string $name, string $model, ?string $img): void
{
    try {
        $stmt = getDb()->prepare('UPDATE avatar SET nameAvatar = :nameAvatar, modelAvatar = :modelAvatar, imgAvatar = :imgAvatar WHERE idAvatar = :idAvatar');
        $stmt->bindParam(':nameAvatar', $name, PDO::PARAM_STR);
        $stmt->bindParam(':modelAvatar', $model, PDO::PARAM_STR);
        $stmt->bindParam(':imgAvatar', $img, PDO::PARAM_STR);
        $stmt->bindParam(':idAvatar', $id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log('Database error in avatarUpdate: ' . $e->getMessage());
    }
}

function avatarDelete(int $id): void
{
    try {
        $stmt = getDb()->prepare('DELETE FROM avatar WHERE idAvatar = :idAvatar');
        $stmt->bindParam(':idAvatar', $id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log('Database error in avatarDelete: ' . $e->getMessage());
    }
}
