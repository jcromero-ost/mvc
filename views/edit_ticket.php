<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Ticket.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/MediosComunicacion.php';
require_once __DIR__ . '/../models/Usuario.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID de ticket inválido.');
}

$ticket_id = intval($_GET['id']);

// Obtener ticket
$ticketObj = new Ticket();
$ticket = $ticketObj->getById($ticket_id);
if (!$ticket) {
    die('Ticket no encontrado.');
}

// Cliente relacionado
$cliente = Cliente::getById($ticket['cliente_id']);

// Medios de comunicación
$medioComObj = new MedioComunicacion();
$medios_comunicacion = $medioComObj->getAll();


// Técnicos
$tecnicos = Usuario::getAllUsuariosSinWebmaster();

// Definir la vista de contenido
$view = 'edit_ticket_content.php';

// Renderizar layout principal
include 'layout.php';
