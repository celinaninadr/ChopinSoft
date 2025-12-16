<?php
require_once '../models/Avatar.php';

class AvatarController {
    private $avatarModel;

    public function __construct() {
        $this->avatarModel = new Avatar();
    }

    public function showCreateForm() {
        include '../views/create_avatar.php';
    }

    public function create() {
        if ($_POST) {
            $name = $_POST['name'] ?? '';
            $model = $_POST['model'] ?? '';
            $img = $_POST['img'] ?? null;

            if ($this->avatarModel->create($name, $model, $img)) {
                header('Location: login.php?success=1');
                exit;
            } else {
                echo "Erreur lors de la création.";
            }
        }
    }
}