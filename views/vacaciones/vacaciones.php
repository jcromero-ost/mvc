<?php
if (session_status() === PHP_SESSION_NONE) 
    session_start(); 


require_once __DIR__ . '/../../models/Usuario.php';

$tecnicosModel = new Usuario();
$tecnicos = $tecnicosModel->getAllUsuarios();

require_once __DIR__ . '/../../models/Vacaciones.php';

$solicitudesModel = new Vacaciones();
$solicitudes = $solicitudesModel->getAllSolicitudes();

$view = './vacaciones/vacaciones_content.php';
include __DIR__ . '/../layout.php';
