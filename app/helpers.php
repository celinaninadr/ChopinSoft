<?php

function redirectTo(string $route, array $params = []): void
{
    $url = '/ChopinSoft/index.php?route=' . urlencode($route);
    if (!empty($params)) {
        $url .= '&' . http_build_query($params);
    }
    header('Location: ' . $url);
    exit;
}

function setFlash(string $msg): void
{
    $_SESSION['flash'] = $msg;
}

function getFlash(): ?string
{
    if (empty($_SESSION['flash'])) {
        return null;
    }
    $msg = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $msg;
}

function render(string $view, array $data = []): void
{
    extract($data);

    ob_start();
    require __DIR__ . '/../view/' . $view . '.php';
    $content = ob_get_clean();

    require __DIR__ . '/../view/layouts/main.php';
}

function requireLogin(): void
{
    if (empty($_SESSION['user'])) {
        setFlash('Connecte-toi.');
        redirectTo('user/login');
    }
}

function requireAdmin(): void
{
    requireLogin();
    if (($_SESSION['user']['userRole'] ?? '') !== 'ADMIN') {
        http_response_code(403);
        echo '403';
        exit;
    }
}
