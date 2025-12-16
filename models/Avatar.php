<?php
require_once __DIR__ . '/../app/pdo.php';

function avatarAll(): array
{
    return getDb()->query('SELECT * FROM avatar ORDER BY idAvatar ASC')->fetchAll();
}

function avatarIsUsed(int $idAvatar): bool
{
    $stmt = getDb()->prepare('SELECT COUNT(*) AS cnt FROM `user` WHERE idAvatar = ?');
    $stmt->execute([$idAvatar]);
    $row = $stmt->fetch();
    return (int)($row['cnt'] ?? 0) > 0;
}

function avatarFind(int $idAvatar): ?array
{
    $stmt = getDb()->prepare('SELECT * FROM avatar WHERE idAvatar = ?');
    $stmt->execute([$idAvatar]);
    $a = $stmt->fetch();
    return $a ?: null;
}

function avatarCreate(string $name, string $model, ?string $img): void
{
    $stmt = getDb()->prepare('INSERT INTO avatar (nameAvatar, modelAvatar, imgAvatar) VALUES (?, ?, ?)');
    $stmt->execute([$name, $model, $img]);
}

function avatarUpdate(int $id, string $name, string $model, ?string $img): void
{
    $stmt = getDb()->prepare('UPDATE avatar SET nameAvatar = ?, modelAvatar = ?, imgAvatar = ? WHERE idAvatar = ?');
    $stmt->execute([$name, $model, $img, $id]);
}

function avatarDelete(int $id): void
{
    $stmt = getDb()->prepare('DELETE FROM avatar WHERE idAvatar = ?');
    $stmt->execute([$id]);
}
