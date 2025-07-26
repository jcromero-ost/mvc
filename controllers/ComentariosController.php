<?php
require_once 'models/Comentario.php';

class ComentariosController {

    public function obtenerComentario($id) {
        $comentario = Comentario::findById($id);
        if ($comentario) {
            echo json_encode(['success' => true, 'comentario' => $comentario['contenido']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Comentario no encontrado']);
        }
    }

    public function guardarComentario() {
        $comentario_id = $_POST['comentario_id'] ?? null;
        $contenido = $_POST['contenido'] ?? null;
        $finalizar = $_POST['finalizar'] ?? false;

        $hora_inicio = $_POST['hora_inicio'] ?? null;
        $hora_fin = $_POST['hora_fin'] ?? null;
        $fecha = $_POST['fecha'] ?? null;

        if ($comentario_id && $contenido) {
            $resultado = Comentario::guardarContenido($comentario_id, $contenido, $finalizar, $hora_inicio, $hora_fin, $fecha);
            echo json_encode(['success' => $resultado]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        }
    }

    public function obtenerComentarios() {
        if (!isset($_GET['ticket_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Ticket ID no proporcionado']);
            exit;
        }

        $ticket_id = intval($_GET['ticket_id']);
        $comentarios = Comentario::getByTicketId($ticket_id);
        echo json_encode($comentarios);
    }

    public function obtenerUno() {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID no proporcionado']);
            return;
        }

        $comentario = Comentario::getById($_GET['id']);
        echo json_encode($comentario);
    }

    public function crearInterno()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $ticketId = $input['ticket_id'] ?? null;
        $usuarioId = $input['usuario_id'] ?? null;

        if (!$ticketId || !$usuarioId) {
            echo json_encode(['success' => false, 'message' => 'Faltan datos']);
            return;
        }

        require_once __DIR__ . '/../models/Comentario.php';
        $comentarioId = Comentario::crearInterno($ticketId, $usuarioId);

        if ($comentarioId) {
            echo json_encode(['success' => true, 'id' => $comentarioId]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear']);
        }
    }

}
