<?php

function getDb(): PDO
{
    static $pdo = null;
    
    {
        try {
            $host = 'localhost';
            $dbname = 'chopin_vr';
            $user = 'root';
            $pass = '';

            $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8mb4';
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            error_log('Database connection error: ' . $e->getMessage());
            throw new Exception('Unable to connect to database');
        }
    }

    return $pdo;
}
