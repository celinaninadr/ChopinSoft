<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/app/pdo.php';
require_once __DIR__ . '/app/helpers.php';

$route = $_GET['route'] ?? 'home/index';
$route = trim($route, '/');

$parts = explode('/', $route);
$controller = $parts[0] ?? 'home';
$action = $parts[1] ?? 'index';

$controllerFile = __DIR__ . '/controllers/' . $controller . '.php';

if (!file_exists($controllerFile)) {
    http_response_code(404);
    echo '404';
    exit;
}

require_once $controllerFile;

$fn = $controller . ucfirst($action);

if (!function_exists($fn)) {
    http_response_code(404);
    echo '404';
    exit;
}

$fn();
