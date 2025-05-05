<?php
if (session_status() === PHP_SESSION_NONE) 
    session_start(); 

require_once(__DIR__ . '/../models/MediosComunicacion.php');

$mediosModel = new MedioComunicacion();
$medios_comunicacion = $mediosModel->getAll();

require_once(__DIR__ . '/../models/Usuario.php');

$tecnicosModel = new Usuario();
$tecnicos = $tecnicosModel->getAllUsuarios();

$view = './crear_ticket_content.php';
include __DIR__ . '/layout.php';
