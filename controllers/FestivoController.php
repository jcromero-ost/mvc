<?php
require_once __DIR__ . '/../models/Festivo.php';

class FestivoController
{
    public static function index()
    {
        // Solo incluimos la vista principal, que ya carga layout y contenido
        include __DIR__ . '/../views/festivos.php';
    }

    public static function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'fecha' => $_POST['fecha'] ?? null,
                'tipo' => $_POST['tipo'] ?? null,
                'descripcion' => $_POST['descripcion'] ?? '',
                'comunidad_autonoma' => $_POST['comunidad_autonoma'] ?? '',
                'municipio' => $_POST['municipio'] ?? '',
            ];

            if ($data['fecha'] && $data['tipo']) {
                $success = Festivo::create($data);
                echo json_encode(['success' => $success]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Faltan campos requeridos.']);
            }
        }
    }

    public static function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = intval($_POST['id']);
            $success = Festivo::delete($id);
            echo json_encode(['success' => $success]);
        }
    }
}
