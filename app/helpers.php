<?php

function redirectTo(string $route, array $params = []): void // https://stackoverflow.com/questions/768431/how-do-i-make-a-redirect-in-php
{
    $url = '/ChopinSoft/index.php?route=' . urlencode($route);
    if (!empty($params)) {
        $url .= '&' . http_build_query($params);
    }
    header('Location: ' . $url);
    exit;
}

function render(string $view, array $data = []): void // https://stackoverflow.com/questions/14143865/render-a-view-in-php

{
    extract($data);

    ob_start();
    require __DIR__ . '/../view/' . $view . '.php';
    $content = ob_get_clean();

    require __DIR__ . '/../view/layouts/main.php';
}

function requireLogin(): void // https://stackoverflow.com/questions/20812141/php-require-login-to-view
{
    if (empty($_SESSION['user'])) {
        $_SESSION['flash'] = 'Connecte-toi.';
        redirectTo('user/login');
    }
}

function requireAdmin(): void
{
    requireLogin();
    if (($_SESSION['user']['userRole'] ?? '') !== 'ADMIN') { // https://stackoverflow.com/questions/16225796/cakephp-how-to-require-admin-role-for-a-specific-page
        http_response_code(403);
        echo '403';
        exit;
    }
}
