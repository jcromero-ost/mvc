<?php
 
if (session_status() === PHP_SESSION_NONE) 
session_start(); 

require_once(__DIR__ . '/../models/Ticket.php');

$ticketModel = new Ticket();
$tickets = $ticketModel->getAllTickets();

foreach ($tickets as &$ticket) {
    $tiempo = $ticketModel->getTiempoTotalSegundos($ticket['id']);
    $ticket['tiempo_total'] = $tiempo['tiempo_total_segundos'] ?? 0;
}

unset($ticket);

require_once(__DIR__ . '/../models/Usuario.php');

$tecnicosModel = new Usuario();
$tecnicos = $tecnicosModel->getAllUsuarios();

$view = 'tickets_content.php';
include __DIR__ . '/layout.php';
