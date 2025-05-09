<?php

require_once 'models/ContratoLaboral.php';

class ContratoLaboralController
{
    public function index()
    {
        include 'views/contratos_laborales.php';
    }

    public function guardar()
    {
        if (
            empty($_POST['usuario_id']) ||
            empty($_POST['tipo']) ||
            empty($_POST['horas_diarias']) ||
            empty($_POST['fecha_inicio'])
        ) {
            echo "Faltan campos obligatorios.";
            return;
        }

        $datos = [
            'usuario_id'     => $_POST['usuario_id'],
            'tipo'           => $_POST['tipo'],
            'horas_diarias'  => $_POST['horas_diarias'],
            'fecha_inicio'   => $_POST['fecha_inicio'],
            'fecha_fin'      => $_POST['fecha_fin'] ?? null,
            'observaciones'  => $_POST['observaciones'] ?? null
        ];

        $exito = ContratoLaboral::crear($datos);

        if ($exito) {
            header('Location: /contratos_laborales?ok=1');
        } else {
            echo "Error al guardar el contrato.";
        }
    }
}
