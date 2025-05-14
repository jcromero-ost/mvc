<?php
if (session_status() === PHP_SESSION_NONE) 
    session_start(); 

require_once(__DIR__ . '/../models/MediosComunicacion.php');

$mediosModel = new MedioComunicacion();
$medios_comunicacion = $mediosModel->getAll();

require_once(__DIR__ . '/../models/Usuario.php');

$tecnicosModel = new Usuario();
$tecnicos = $tecnicosModel->getAllUsuarios();

require_once(__DIR__ . '/../models/Cliente.php');

$clienteModel = new Cliente();
$clientes = $clienteModel->getAllClientes();

require_once(__DIR__ . '/../models/Ticket.php');

$ticket_id = $_GET['id'] ?? null;

if (!$ticket_id) {
    // Puedes redirigir o mostrar un error
    header("Location: /tickets_pendientes");
    exit;
}

$ticketModel = new Ticket();
$ticket = $ticketModel->getById($ticket_id);

$comentarios = $ticketModel->getAllComentarios($ticket_id);

$tiempo_total = $ticketModel->getTiempoTotal($ticket_id);
$segundos = (int) $tiempo_total['tiempo_total'];

$horas = floor($segundos / 3600);
$minutos = floor(($segundos % 3600) / 60);
$restoSegundos = $segundos % 60;

$tiempoFormateado = sprintf('%02d:%02d:%02d', $horas, $minutos, $restoSegundos);


$view = './editar_ticket_content.php';
include __DIR__ . '/layout.php';
