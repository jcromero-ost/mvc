<?php

require_once __DIR__ . '/../models/Vacaciones.php';
require_once __DIR__ . '/../models/Usuario.php';

class VacacionesController
{
    // Carga inicial de la vista con buscador de usuarios
    public function index()
    {
        $usuarioModel = new Usuario();
        $usuarios = $usuarioModel->obtenerUsuariosActivos();

        include __DIR__ . '/../views/vacaciones/vacaciones.php';
    }

    public function store() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario_id = $_SESSION['id'];
            $fecha_inicio = $_POST['fecha_inicio'] ?? '';
            $fecha_fin = $_POST['fecha_fin'] ?? '';
            $motivo = $_POST['motivo'] ?? '';
            $fecha_creacion = date('Y-m-d');

            // Validaciones
            if (empty($fecha_inicio) || empty($fecha_fin) || empty($motivo)) {
                $_SESSION['error'] = 'Todos los campos obligatorios deben completarse.';
                header('Location: /vacaciones');
                exit;
            }

            $solicitud = new Vacaciones();
            $solicitud->create([
                'usuario_id' => $usuario_id,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'motivo' => $motivo,
                'fecha_creacion' => $fecha_creacion
            ]);


            $_SESSION['success'] = 'Solicitud creada correctamente';
            header('Location: /vacaciones');
            exit;
        }

        // Si entra por GET o sin POST válido, redirigir
        header('Location: /vacaciones');
        exit;
    }

public function getSolicitudesPorEstado()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $estado = $_POST['estado'] ?? '';

        if (empty($estado)) {
            // Respuesta JSON con error si no envían estado
            echo json_encode([
                'success' => false,
                'message' => 'Falta el parámetro estado.'
            ]);
            exit;
        }

        $vacacionesModel = new Vacaciones();
        $vacaciones = $vacacionesModel->getByEstado($estado);

        echo json_encode([
            'success' => true,
            'data' => $vacaciones
        ]);
        exit;
    }

    // Si no es POST, devolver error o redirigir
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ]);
    exit;
}
}
