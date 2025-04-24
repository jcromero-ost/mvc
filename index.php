<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/Router.php';

$router = new Router();

require_once __DIR__ . '/routes/web.php';

$router->resolve();
