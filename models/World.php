<?php
require_once 'Database.php';

class World {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM world");
        return $stmt->fetchAll();
    }
}