<?php
 
if (session_status() === PHP_SESSION_NONE) 
session_start(); 

require_once(__DIR__ . '/../models/unauth.php');

require_once(__DIR__ . '/../models/Cliente.php');

$clienteModel = new Cliente();
$clientes = $clienteModel->getAllClientes();

require_once(__DIR__ . '/../models/Ticket.php');

$view = 'clientes_historial_content.php';
include __DIR__ . '/layout.php';



