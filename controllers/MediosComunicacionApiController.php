<?php
require_once __DIR__ . '/../models/MediosComunicacion.php';

class MediosComunicacionApiController
{
    public static function index()
    {
        header('Content-Type: application/json');
        $modelo = new MedioComunicacion();
        echo json_encode($modelo->getAll());
    }

    public static function show()
    {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID requerido']);
            return;
        }
        $modelo = new MedioComunicacion();
        echo json_encode($modelo->getById($id));
    }

    public static function store()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['nombre'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Nombre requerido']);
            return;
        }
        $modelo = new MedioComunicacion();
        $modelo->create($data['nombre']);
        echo json_encode(['success' => true]);
    }

    public static function update()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['id'], $data['nombre'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID y nombre requeridos']);
            return;
        }
        $modelo = new MedioComunicacion();
        $modelo->update($data['id'], $data['nombre']);
        echo json_encode(['success' => true]);
    }

    public static function delete()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID requerido']);
            return;
        }
        $modelo = new MedioComunicacion();
        $modelo->delete($data['id']);
        echo json_encode(['success' => true]);
    }
}
