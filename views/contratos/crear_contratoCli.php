<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

// Vista del formulario de creación de contrato para cliente (sin cliente preseleccionado)
$view = 'contratos/crear_contratoCli_content.php';
include __DIR__ . '/../layout.php';
