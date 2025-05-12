<?php

require_once __DIR__ . '/../models/ResumenHoras.php';
require_once __DIR__ . '/../models/Usuario.php';

class ResumenHorasController
{
    // Carga inicial de la vista con buscador de usuarios
    public function index()
    {
        $usuarioModel = new Usuario();
        $usuarios = $usuarioModel->obtenerUsuariosActivos();

        $view = './resumen_horas/index.php';
        include __DIR__ . '/../views/layout.php';
    }

    // Ruta AJAX para obtener tabla de resumen por usuario y año
    public function resumenPorUsuario()
    {
        if (!isset($_GET['usuario_id'], $_GET['anio'])) {
            http_response_code(400);
            echo 'Parámetros requeridos: usuario_id y anio.';
            return;
        }

        $usuarioId = intval($_GET['usuario_id']);
        $anio = intval($_GET['anio']);

        if ($usuarioId <= 0 || $anio <= 2000) {
            http_response_code(422);
            echo 'Parámetros inválidos.';
            return;
        }

        $resumen = ResumenHoras::obtenerResumenMensualPorUsuario($usuarioId, $anio);
        require __DIR__ . '/../views/resumen_horas/table_content.php';
    }

    // (Obsoleto, pero lo dejo por si lo necesitas temporalmente)
    public function filtrar()
    {
        http_response_code(410); // Gone
        echo 'Este método ya no está en uso.';
    }
}
