<?php
require_once 'controllers/UserController.php';

$controller = new UserController();
$action = $_GET['action'] ?? 'show';

if ($action === 'login') {
    $controller->loginAndSelect();
} else {
    $controller->showLoginForm();
}