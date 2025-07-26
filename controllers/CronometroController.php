<?php
require_once 'models/Cronometro.php';

class CronometroController {

    public function iniciar() {
        // Validar que sea una petición POST y existan los datos necesarios
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticket_id = $_POST['ticket_id'] ?? null;
            $usuario_id = $_SESSION['user_id'] ?? null;

            if ($ticket_id && $usuario_id) {
                $comentario_id = Cronometro::iniciarComentario($ticket_id, $usuario_id);

                if ($comentario_id) {
                    echo json_encode([
                        'success' => true,
                        'comentario_id' => $comentario_id,
                        'message' => 'Comentario iniciado correctamente'
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al iniciar el comentario']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        }
    }

    public static function guardarContenido($comentario_id, $contenido) {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE comentarios SET contenido = ? WHERE id = ?");
        return $stmt->execute([$contenido, $comentario_id]);
    }

}
