<?php
require_once __DIR__ . '/../app/pdo.php';

function worldAll(): array
{
    try {
        $sql = 'SELECT * FROM world ORDER BY idWorld ASC';
        $stmt = getDb()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log('Erreur base de données dans worldAll: ' . $e->getMessage());
        return [];
    }
}

function worldIsUsed(int $idWorld): bool
{
    try {
        $stmt = getDb()->prepare('SELECT COUNT(*) AS cnt FROM `user` WHERE idWorld = :idWorld');
        $stmt->bindParam(':idWorld', $idWorld, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        $count = isset($row['cnt']) ? $row['cnt'] : 0;
        return (int)$count > 0;
    } catch (PDOException $e) {
        error_log('Erreur base de données dans worldIsUsed: ' . $e->getMessage());
        return false;
    }
}

function worldFind(int $idWorld): ?array
{
    try {
        $stmt = getDb()->prepare('SELECT * FROM world WHERE idWorld = :idWorld');
        $stmt->bindParam(':idWorld', $idWorld, PDO::PARAM_INT);
        $stmt->execute();
        $w = $stmt->fetch();
        return $w ?: null;
    } catch (PDOException $e) {
        error_log('Erreur base de données dans worldFind: ' . $e->getMessage());
        return null;
    }
}

function worldCreate(string $name, ?string $img, string $url): void
{
    try {
        $stmt = getDb()->prepare('INSERT INTO world (nameWorld, imgWorld, urlWorld) VALUES (:nameWorld, :imgWorld, :urlWorld)');
        $stmt->bindParam(':nameWorld', $name, PDO::PARAM_STR);
        $stmt->bindParam(':imgWorld', $img, PDO::PARAM_STR);
        $stmt->bindParam(':urlWorld', $url, PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log('Erreur base de données dans worldCreate: ' . $e->getMessage());
    }
}

function worldUpdate(int $id, string $name, ?string $img, string $url): void
{
    try {
        $stmt = getDb()->prepare('UPDATE world SET nameWorld = :nameWorld, imgWorld = :imgWorld, urlWorld = :urlWorld WHERE idWorld = :idWorld');
        $stmt->bindParam(':nameWorld', $name, PDO::PARAM_STR);
        $stmt->bindParam(':imgWorld', $img, PDO::PARAM_STR);
        $stmt->bindParam(':urlWorld', $url, PDO::PARAM_STR);
        $stmt->bindParam(':idWorld', $id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log('Erreur base de données dans worldUpdate: ' . $e->getMessage());
    }
}

function worldDelete(int $id): void
{
    try {
        $stmt = getDb()->prepare('DELETE FROM world WHERE idWorld = :idWorld');
        $stmt->bindParam(':idWorld', $id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        error_log('Erreur base de données dans worldDelete: ' . $e->getMessage());
    }
}
