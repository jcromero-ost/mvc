<?php
require_once 'TicketController.php';

$action = $_GET['action'] ?? '';

$controller = new TicketController();

switch ($action) {
    case 'actualizar':
        $controller->actualizar();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        break;
}
