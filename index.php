<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/Router.php';

$router = new Router();

require_once __DIR__ . '/routes/web.php';

$router->resolve();
