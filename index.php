<?php
require_once 'controllers/AvatarController.php';

$controller = new AvatarController();
$action = $_GET['action'] ?? 'show';

if ($action === 'create') {
    $controller->create();
} else {
    $controller->showCreateForm();
}