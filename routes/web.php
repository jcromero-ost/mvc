<?php
require_once __DIR__ . '/../controllers/UsuarioController.php';

$r = $_GET['r'] ?? 'usuarios';
$controller = new UsuarioController();

switch ($r) {
    case 'usuarios':
        $controller->index();
        break;
    case 'crear_usuario':
        $controller->create();
        break;
    case 'store_usuario':
        $controller->store();
        break;
    default:
        echo "Página no encontrada";
}
