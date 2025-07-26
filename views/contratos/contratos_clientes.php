<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

$view = 'contratos/contratos_clientes_content.php';
include_once __DIR__ . '/../layout.php';
