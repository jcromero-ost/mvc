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

$view = './editar_ticket_content.php';
include __DIR__ . '/layout.php';
