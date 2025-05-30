<?php
if (session_status() === PHP_SESSION_NONE) 
    session_start(); 

require_once(__DIR__ . '/../../models/Usuario.php');
require_once(__DIR__ . '/../../models/ResumenHoras.php');
require_once(__DIR__ . '/../../models/ContratoLaboral.php');


$usuarioModel = new Usuario();
$usuario = $usuarioModel->getById($_SESSION['id']);



$horasModel = new ResumenHoras();
$resumen = $horasModel->obtenerTotalesAnualesPorUsuario($_SESSION['id'], date('Y'));

function formatoResumen($hora) {
    if (!$hora || $hora === '00:00:00' || $hora === '-00:00:00') {
        return '0 horas';
    }

    $esNegativo = false;
    if (strpos($hora, '-') === 0) {
        $esNegativo = true;
        $hora = substr($hora, 1);
    }

    list($h, $m, $s) = explode(':', $hora);
    $horas = (int)$h;
    $minutos = (int)$m;

    $resultado = [];
    if ($horas > 0) {
        $resultado[] = $horas . ' ' . ($horas === 1 ? 'hora' : 'horas');
    }
    if ($minutos > 0) {
        $resultado[] = $minutos . ' ' . ($minutos === 1 ? 'minuto' : 'minutos');
    }

    $texto = implode(' y ', $resultado);

    if ($esNegativo) {
        return 'Debes ' . ($texto ?: '0 horas');
    } else {
        return $texto ?: '0 horas';
    }
}


// Puedes agregar lógica extra aquí si es necesario

$view = 'perfil_user/perfil_user_content.php';
include __DIR__ . '/../layout.php';
