<?php
if (session_status() === PHP_SESSION_NONE) 
    session_start();

$view = 'xgest/crear_pedido_test_content.php';
require_once __DIR__ . '/../layout.php';
