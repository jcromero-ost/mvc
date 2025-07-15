<?php
require_once(__DIR__ . '/Database.php');
require_once __DIR__ . '/../config/config_xgest.php';


class Ticket {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAllTickets() {
        $stmt = $this->db->prepare("SELECT * FROM tickets ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllTicketsNoFinalizados($tecnico_id) {
        $idDeptAdmin = 3; // Id departamento Administración (ajusta según tu BD)

        if ($_SESSION['dept'] != '3' && $_SESSION['dept'] != '2') {
            // Solo los tickets asignados al técnico o sin asignar
            // Excluir tickets asignados al departamento Administración
            $stmt = $this->db->prepare("
                SELECT DISTINCT t.*
                FROM tickets t
                LEFT JOIN ticket_asignaciones a ON t.id = a.ticket_id AND a.tipo = 'tecnico'
                WHERE (a.ref_id = :tecnico_id OR a.ref_id IS NULL)
                AND t.estado IN ('pendiente', 'en_revision')
                AND t.id NOT IN (
                    SELECT ticket_id 
                    FROM ticket_asignaciones 
                    WHERE tipo = 'departamento' AND ref_id = :idDeptAdmin
                )
                ORDER BY t.id ASC
            ");
            $stmt->bindParam(':tecnico_id', $tecnico_id, PDO::PARAM_INT);
            $stmt->bindParam(':idDeptAdmin', $idDeptAdmin, PDO::PARAM_INT);
        } else {
            // Admins: todos los tickets no finalizados (sin filtro)
            $stmt = $this->db->prepare("
                SELECT * FROM tickets
                WHERE estado IN ('pendiente', 'en_revision')
                ORDER BY id ASC
            ");
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tickets WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByIdCliente($cliente_id) {
        $stmt = $this->db->prepare("SELECT t.*, m.nombre AS medio_nombre, u.nombre AS tecnico_nombre
                                        FROM tickets t
                                        JOIN medios_comunicacion m ON t.medio_id = m.id
                                        JOIN usuarios u ON t.tecnico_id = u.id
                                        WHERE cliente_id = :cliente_id");
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTicketNoAsignados() {
    $stmt = $this->db->prepare("
        SELECT t.*
        FROM tickets t
        LEFT JOIN ticket_asignaciones a ON t.id = a.ticket_id
        WHERE a.ticket_id IS NULL
        ORDER BY t.fecha_inicio DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function create($data, $devolver_id = false) {
        $stmt = $this->db->prepare("INSERT INTO tickets 
            (medio_id, cliente_id, fecha_inicio, descripcion, estado, usuario_creador_id) 
            VALUES 
            (:medio_id, :cliente_id, :fecha_inicio, :descripcion, :estado, :usuario_creador_id)");

        $stmt->bindParam(':medio_id', $data['medio_id']);
        $stmt->bindParam(':cliente_id', $data['cliente_id']);
        $stmt->bindParam(':fecha_inicio', $data['fecha_inicio']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':usuario_creador_id', $data['usuario_creador_id']);

        if ($stmt->execute()) {
            return $devolver_id ? $this->db->lastInsertId() : true;
        }

        return false;
    }



    public static function update($data) {
    $db = Database::connect();

    $sql = "UPDATE tickets 
            SET medio_id = :medio_id, 
                descripcion = :descripcion, 
                estado = :estado 
            WHERE id = :id";

    $stmt = $db->prepare($sql);

    $stmt->bindParam(':medio_id', $data['medio_id']);
    $stmt->bindParam(':descripcion', $data['descripcion']);
    $stmt->bindParam(':estado', $data['estado']);
    $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);

    return $stmt->execute();
}


    public static function delete($id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM tickets WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getAllComentarios($ticket_id) {
        $stmt = $this->db->prepare("
            SELECT c.*, u.nombre AS nombre_usuario
            FROM comentarios c
            LEFT JOIN usuarios u ON c.usuario_id = u.id
            WHERE c.ticket_id = :ticket_id 
            ORDER BY c.fecha DESC, c.hora_inicio DESC
        ");
        $stmt->bindParam(':ticket_id', $ticket_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateComentarioSoloFecha($data) {
        $stmt = $this->db->prepare("UPDATE comentarios SET contenido = :contenido, hora_fin = :hora_fin WHERE id = :id");
        $stmt->bindParam(':contenido', $data['contenido']);
        $stmt->bindParam(':hora_fin', $data['hora_fin']);
        $stmt->bindParam(':id', $data['id']);
        return $stmt->execute();
    }

    public function createComentario($data) {
    $stmt = $this->db->prepare("
        INSERT INTO comentarios (ticket_id, fecha, contenido, tipo, usuario_id, hora_inicio, hora_fin)
        VALUES (:ticket_id, :fecha, :contenido, :tipo, :usuario_id, :hora_inicio, :hora_fin)
    ");

    $stmt->bindParam(':ticket_id', $data['ticket_id']);
    $stmt->bindParam(':fecha', $data['fecha']);
    $stmt->bindParam(':contenido', $data['contenido']);
    $stmt->bindParam(':tipo', $data['tipo']);
    $stmt->bindParam(':usuario_id', $_SESSION['user']['id']); // o $data['usuario_id'] si lo pasas desde el controlador
    $stmt->bindValue(':hora_inicio', $data['hora_inicio'] ?? null);
    $stmt->bindValue(':hora_fin', $data['hora_fin'] ?? null);

    if ($stmt->execute()) {
        return $this->db->lastInsertId();
    } else {
        return false;
    }
}


    public function createComentarioSoloFecha($data) {
        $sql = "INSERT INTO comentarios (ticket_id, usuario_id, fecha, hora_inicio, tipo) 
                VALUES (:ticket_id, :usuario_id, :fecha, :hora_inicio, 'trabajo')";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':ticket_id', $data['ticket_id']);
        $stmt->bindValue(':usuario_id', $data['usuario_id']);
        $stmt->bindValue(':fecha', $data['fecha']);
        $stmt->bindValue(':hora_inicio', $data['hora_inicio']);

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }


    public static function deleteComentarios($id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM comentarios WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function updateComentarios($data) {
        $db = Database::connect();

        $sql = "UPDATE comentarios SET contenido = :contenido";
        if (isset($data['hora_fin']) && !empty($data['hora_fin'])) {
            $sql .= ", hora_fin = :hora_fin";
        }
        $sql .= " WHERE id = :id";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':contenido', $data['contenido']);
        if (isset($data['hora_fin']) && !empty($data['hora_fin'])) {
            $stmt->bindParam(':hora_fin', $data['hora_fin']);
        }
        $stmt->bindParam(':id', $data['id']);
        return $stmt->execute();
    }

    


    public function updateEstado($data) {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE tickets SET estado = :estado, fecha_fin = :fecha_fin WHERE id = :id");
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':fecha_fin', $data['fecha_fin']);
        $stmt->bindParam(':id', $data['id']);
        return $stmt->execute();
    }

    /*___NO SE USARA POR AHORA___*/
    /*public function createCronometro($data) {
        $stmt = $this->db->prepare("
            INSERT INTO cronometro (ticket_id, fecha, tiempo, usuario_id, hora_inicio, hora_fin) 
            VALUES (:ticket_id, :fecha, :tiempo, :usuario_id, :hora_inicio, :hora_fin)
        ");

        // Usar valores nulos si están vacíos
        $stmt->bindValue(':ticket_id', $data['ticket_id'], PDO::PARAM_INT);
        $stmt->bindValue(':fecha', $data['fecha']);
        $stmt->bindValue(':tiempo', $data['tiempo'] ?? null);
        $stmt->bindValue(':usuario_id', $data['usuario_id'] ?? null, PDO::PARAM_INT);
        $stmt->bindValue(':hora_inicio', $data['hora_inicio'] ?? null);
        $stmt->bindValue(':hora_fin', $data['hora_fin'] ?? null);

        return $stmt->execute();
    }*/

    /*___NO SE USARA POR AHORA___*/
    /*public function getAllCronometro($ticket_id) {
        $stmt = $this->db->prepare("SELECT c.tiempo, u.nombre as nombre_usuario FROM cronometro c JOIN usuarios u ON c.usuario_id = u.id WHERE c.ticket_id = :ticket_id");
        $stmt->bindParam(':ticket_id', $ticket_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }*/

    public function getTiempoTotal($ticket_id) {
        $stmt = $this->db->prepare("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(hora_fin, hora_inicio)))) AS tiempo_total FROM comentarios WHERE ticket_id = :ticket_id AND hora_fin IS NOT NULL AND hora_inicio IS NOT NULL");
        $stmt->bindParam(':ticket_id', $ticket_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*___NO SE USARA POR AHORA___*/
    /*public function getTiempoTotalSegundos($ticket_id) {
        $stmt = $this->db->prepare("SELECT SUM(TIME_TO_SEC(tiempo)) AS tiempo_total_segundos FROM cronometro WHERE ticket_id = :ticket_id");
        $stmt->bindParam(':ticket_id', $ticket_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }*/

    public function getByUsuario($usuario) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tickets WHERE tecnico_id = :tecnico_id AND estado NOT IN ('finalizado', 'albaranado')");
            $stmt->bindParam(':tecnico_id', $usuario, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: [];
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }

    

    
}
