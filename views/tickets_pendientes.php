<?php
 
if (session_status() === PHP_SESSION_NONE) 
session_start(); 

require_once(__DIR__ . '/../models/Ticket.php');
require_once(__DIR__ . '/../models/MediosComunicacion.php');
require_once(__DIR__ . '/../models/TicketAsignacion.php');
require_once(__DIR__ . '/../models/Departamento.php');

$ticketModel = new Ticket();
$tickets = $ticketModel->getAllTicketsNoFinalizados($_SESSION['id']);

$AsignacionesModel = new TicketAsignacion();
$asignaciones = $AsignacionesModel->getAllAsignaciones();

// Cargar departamentos
$departamentoModel = new Departamento();
$departamentos = $departamentoModel->getAllDepartamentosSinWebmaster();

// Cargar medios de comunicación
$medioModel = new MedioComunicacion();
$medios_comunicacion = $medioModel->getAll();

$asignacionesPorTicket = [];

foreach ($asignaciones as $asignacion) {
    $ticketId = $asignacion['ticket_id'];
    if (!isset($asignacionesPorTicket[$ticketId])) {
        $asignacionesPorTicket[$ticketId] = [];
    }
    $asignacionesPorTicket[$ticketId][] = $asignacion;
}

require_once(__DIR__ . '/../models/Usuario.php');

$tecnicosModel = new Usuario();
$tecnicos = $tecnicosModel->getAllUsuariosSinWebmaster();

$view = 'tickets_pendientes_content.php';
include __DIR__ . '/layout.php';