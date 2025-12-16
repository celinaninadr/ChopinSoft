<?php
require_once 'Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function updateWorldAndAvatar($idUser, $idAvatar, $idWorld) {
        $stmt = $this->db->prepare("UPDATE user SET idAvatar = ?, idWorld = ? WHERE idUser = ?");
        return $stmt->execute([$idAvatar, $idWorld, $idUser]);
    }
}