<?php
require_once __DIR__ . '/../app/pdo.php';

function worldAll(): array
{
    return getDb()->query('SELECT * FROM world ORDER BY idWorld ASC')->fetchAll();
}

function worldIsUsed(int $idWorld): bool
{
    $stmt = getDb()->prepare('SELECT COUNT(*) AS cnt FROM `user` WHERE idWorld = ?');
    $stmt->execute([$idWorld]);
    $row = $stmt->fetch();
    return (int)($row['cnt'] ?? 0) > 0;
}

function worldFind(int $idWorld): ?array
{
    $stmt = getDb()->prepare('SELECT * FROM world WHERE idWorld = ?');
    $stmt->execute([$idWorld]);
    $w = $stmt->fetch();
    return $w ?: null;
}

function worldCreate(string $name, ?string $img, string $url): void
{
    $stmt = getDb()->prepare('INSERT INTO world (nameWorld, imgWorld, urlWorld) VALUES (?, ?, ?)');
    $stmt->execute([$name, $img, $url]);
}

function worldUpdate(int $id, string $name, ?string $img, string $url): void
{
    $stmt = getDb()->prepare('UPDATE world SET nameWorld = ?, imgWorld = ?, urlWorld = ? WHERE idWorld = ?');
    $stmt->execute([$name, $img, $url, $id]);
}

function worldDelete(int $id): void
{
    $stmt = getDb()->prepare('DELETE FROM world WHERE idWorld = ?');
    $stmt->execute([$id]);
}
