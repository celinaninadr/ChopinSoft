<?php
require_once 'Database.php';

class Avatar {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM avatar");
        return $stmt->fetchAll();
    }

    public function create($name, $model, $img = null) {
        $stmt = $this->db->prepare("INSERT INTO avatar (nameAvatar, modelAvatar, imgAvatar) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $model, $img]);
    }
}