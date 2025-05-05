<?php

require_once __DIR__ . '/../models/CalendarioLaboral.php';

class CalendarioLaboralController
{
    public function index()
{
    $vista = $_GET['vista'] ?? 'mensual';
    $anio = isset($_GET['anio']) ? intval($_GET['anio']) : date('Y');
    $mes = isset($_GET['mes']) ? intval($_GET['mes']) : date('n');
    $semana = isset($_GET['semana']) ? intval($_GET['semana']) : 1;

    if ($vista === 'anual') {
        if ($anio < 2000 || $anio > 2100) {
            $calendario = null;
        } else {
            $calendario = ['anio' => $anio]; // solo se necesita el año
        }
    } elseif ($vista === 'semanal') {
        if ($anio < 2000 || $anio > 2100 || $semana < 1 || $semana > 53) {
            $calendario = null;
        } else {
            $calendario = [
                'anio' => $anio,
                'mes' => $mes,
                'semana' => $semana
            ];
        }
    } else {
        if ($anio < 2000 || $anio > 2100 || $mes < 1 || $mes > 12) {
            $calendario = null;
        } else {
            $calendario = CalendarioLaboral::generarCalendario($anio, $mes);
        }
    }

    $view = './calendario_laboral_content.php';
    include 'views/layout.php';
}

}
