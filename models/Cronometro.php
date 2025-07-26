<?php
require_once 'Database.php';

class Cronometro {

    public static function iniciarComentario($ticket_id, $usuario_id) {
        $db = Database::connect();

        $sql = "INSERT INTO comentarios (ticket_id, usuario_id, hora_inicio, fecha, tipo) VALUES (:ticket_id, :usuario_id, :hora_inicio, :fecha, 'normal')";
        $stmt = $db->prepare($sql);

        $hora_inicio = date('H:i:s');
        $fecha = date('Y-m-d');

        $stmt->bindParam(':ticket_id', $ticket_id, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(':hora_inicio', $hora_inicio);
        $stmt->bindParam(':fecha', $fecha);

        if ($stmt->execute()) {
            return $db->lastInsertId();
        } else {
            return false;
        }
    }
}
