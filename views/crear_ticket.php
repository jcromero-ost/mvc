<?php
if (session_status() === PHP_SESSION_NONE) 
    session_start(); 

require_once(__DIR__ . '/../models/Cliente.php');
require_once(__DIR__ . '/../models/MediosComunicacion.php');
require_once(__DIR__ . '/../models/Usuario.php'); // Asumiendo que tienes una clase Usuario
require_once(__DIR__ . '/../models/Departamento.php');

// Cargar clientes
$clienteModel = new Cliente();
$clientes = $clienteModel->getAllClientes();

// Cargar medios de comunicación
$medioModel = new MedioComunicacion();
$medios_comunicacion = $medioModel->getAll();

// Cargar departamentos
$departamentoModel = new Departamento();
$departamentos = $departamentoModel->getAllDepartamentosSinWebmaster();

// Cargar técnicos (asume que hay un método para obtenerlos)
$tecnicos = Usuario::getAllUsuariosSinWebmaster();

// Cargar contenido
$view = './crear_ticket_content.php';
include __DIR__ . '/layout.php';
