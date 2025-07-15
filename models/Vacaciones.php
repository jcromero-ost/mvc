<?php

require_once __DIR__ . '/Database.php';

class Vacaciones
{
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM vacaciones WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener todos los usuarios
    public function getAllSolicitudes() {
        if ($_SESSION['dept'] == 2 || $_SESSION['dept'] == 3) {
            // Si el departamento es 2 o 3, mostrar todas las solicitudes
            $stmt = $this->db->prepare("SELECT v.*, u.nombre AS usuario_nombre, r.nombre AS revisado_por
                FROM vacaciones v
                LEFT JOIN usuarios u ON v.usuario_id = u.id
                LEFT JOIN usuarios r ON v.revisado_por = r.id");
            $stmt->execute();
        } else {
            // Si es otro departamento, mostrar solo las solicitudes del usuario logueado
            $stmt = $this->db->prepare("SELECT v.*, u.nombre AS usuario_nombre, r.nombre AS revisado_por
                FROM vacaciones v
                LEFT JOIN usuarios u ON v.usuario_id = u.id
                LEFT JOIN usuarios r ON v.revisado_por = r.id
                WHERE v.usuario_id = :usuario_id");
            $stmt->bindParam(':usuario_id', $_SESSION['id'], PDO::PARAM_INT);
            $stmt->execute();
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los usuarios
    public function getByEstado($estado) {
        try {
            $stmt = $this->db->prepare("SELECT v.*, u.nombre AS usuario_nombre FROM vacaciones v JOIN usuarios u ON v.usuario_id = u.id WHERE estado = :estado");
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: [];
        } catch (PDOException $e) {
            error_log("Error en getByEstado: " . $e->getMessage());
            return false;
        }
    }

    // Crear nueva solicitud
    public function create($data) {
        // Preparar la consulta de inserción
        $stmt = $this->db->prepare("
            INSERT INTO vacaciones 
                (usuario_id, fecha_inicio, fecha_fin, motivo, fecha_creacion)
            VALUES 
                (:usuario_id, :fecha_inicio, :fecha_fin, :motivo, :fecha_creacion)
        ");

        $stmt->bindParam(':usuario_id', $data['usuario_id']);
        $stmt->bindParam(':fecha_inicio', $data['fecha_inicio']);
        $stmt->bindParam(':fecha_fin', $data['fecha_fin']);
        $stmt->bindParam(':motivo', $data['motivo']);
        $stmt->bindParam(':fecha_creacion', $data['fecha_creacion']);

        return $stmt->execute();
    }

    // Crear nueva solicitud
    public function updateEstado($data) {
        // Preparar la consulta de inserción
        $stmt = $this->db->prepare("UPDATE vacaciones SET estado = :estado, revisado_por = :revisado_por, rechazo_motivo = :rechazo_motivo WHERE id= :id");

        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':revisado_por', $data['revisado_por']);
        $stmt->bindParam(':rechazo_motivo', $data['rechazo_motivo']);
        $stmt->bindParam(':id', $data['id']);

        return $stmt->execute();
    }

    public function insertarEventosVacaciones($usuario_id, $fecha_inicio, $fecha_fin)
    {
        $fecha_actual = new DateTime($fecha_inicio);
        $fecha_fin_dt = new DateTime($fecha_fin);
        $created_at = date('Y-m-d H:i:s');

        while ($fecha_actual <= $fecha_fin_dt) {
            $evento_fecha = $fecha_actual->format('Y-m-d');

            // Insertar evento de inicio de vacaciones a las 00:00
            $stmt = $this->db->prepare("
                INSERT INTO registro_horarios (user_id, tipo_evento, fecha_hora, created_at)
                VALUES (:user_id, :tipo_evento, :fecha_hora, :created_at)
            ");
            $stmt->execute([
                ':user_id' => $usuario_id,
                ':tipo_evento' => 'inicio_vacaciones',
                ':fecha_hora' => $evento_fecha . ' 00:00:00',
                ':created_at' => $created_at
            ]);

            // Insertar evento de fin de vacaciones a las 08:00
            $stmt = $this->db->prepare("
                INSERT INTO registro_horarios (user_id, tipo_evento, fecha_hora, created_at)
                VALUES (:user_id, :tipo_evento, :fecha_hora, :created_at)
            ");
            $stmt->execute([
                ':user_id' => $usuario_id,
                ':tipo_evento' => 'fin_vacaciones',
                ':fecha_hora' => $evento_fecha . ' 08:00:00',
                ':created_at' => $created_at
            ]);

            $fecha_actual->modify('+1 day');
        }
    }


}