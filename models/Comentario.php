<?php
class Comentario {

    public static function findById($id) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT contenido FROM comentarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function guardarContenido($comentario_id, $contenido, $finalizar = false, $hora_inicio = null, $hora_fin = null, $fecha = null) {
        $db = Database::connect();

        $campos = ['contenido = ?'];
        $parametros = [$contenido];

        // Si se pasa hora_inicio, actualizarlo
        if (!empty($hora_inicio)) {
            $campos[] = 'hora_inicio = ?';
            $parametros[] = $hora_inicio;
        }

        // Si se pasa hora_fin manual o finalizamos, actualizar
        if (!empty($hora_fin)) {
            $campos[] = 'hora_fin = ?';
            $parametros[] = $hora_fin;
        } elseif ($finalizar) {
            $campos[] = 'hora_fin = ?';
            $parametros[] = date('H:i:s');
        }

        // Si se pasa fecha, actualizar
        if (!empty($fecha)) {
            $campos[] = 'fecha = ?';
            $parametros[] = $fecha;
        }

        $parametros[] = $comentario_id;
        $sql = "UPDATE comentarios SET " . implode(', ', $campos) . " WHERE id = ?";
        $stmt = $db->prepare($sql);

        return $stmt->execute($parametros);
    }

    public static function getByTicketId($ticket_id) {
        $db = Database::connect();
        $stmt = $db->prepare(
            "SELECT c.*, u.nombre AS nombre_usuario 
            FROM comentarios c 
            JOIN usuarios u ON c.usuario_id = u.id
            WHERE c.ticket_id = ?
            ORDER BY c.fecha DESC, c.hora_inicio DESC"
        );
        $stmt->execute([$ticket_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM comentarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function crearInterno($ticketId, $usuarioId)
    {
        $db = Database::connect();

        $stmt = $db->prepare("
            INSERT INTO comentarios (ticket_id, usuario_id, tipo, hora_inicio, fecha)
            VALUES (:ticket_id, :usuario_id, 'interno', :hora_inicio, :fecha)
        ");

        $horaInicio = date('H:i:s'); // Hora actual en formato HH:MM:SS
        $fecha = date('Y-m-d');

        $stmt->bindParam(':ticket_id', $ticketId);
        $stmt->bindParam(':usuario_id', $usuarioId);
        $stmt->bindParam(':hora_inicio', $horaInicio);
        $stmt->bindParam(':fecha', $fecha);

        if ($stmt->execute()) {
            return $db->lastInsertId();
        }

        return false;
    }


}
