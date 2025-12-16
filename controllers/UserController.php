<?php
require_once '../models/User.php';
require_once '../models/Avatar.php';
require_once '../models/World.php';

class UserController {
    private $userModel, $avatarModel, $worldModel;

    public function __construct() {
        $this->userModel = new User();
        $this->avatarModel = new Avatar();
        $this->worldModel = new World();
    }

    public function showLoginForm() {
        $avatars = $this->avatarModel->getAll();
        $worlds = $this->worldModel->getAll();
        include '../views/login_form.php';
    }

    public function loginAndSelect() {
        if ($_POST) {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $idAvatar = $_POST['idAvatar'] ?? null;
            $idWorld = $_POST['idWorld'] ?? null;

            $user = $this->userModel->login($username, $password);
            if ($user && $idAvatar && $idWorld) {
                $this->userModel->updateWorldAndAvatar($user['idUser'], $idAvatar, $idWorld);
                session_start();
                $_SESSION['user'] = $user;
                header("Location: welcome.php"); // ou autre page après login
                exit;
            } else {
                $error = "Identifiants ou sélection invalide.";
                $avatars = $this->avatarModel->getAll();
                $worlds = $this->worldModel->getAll();
                include '../views/login_form.php';
            }
        }
    }
}