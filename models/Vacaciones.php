<?php

require_once __DIR__ . '/Database.php';

class Vacaciones
{
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    // Obtener todos los usuarios
    public function getAllSolicitudes() {
        $stmt = $this->db->prepare("SELECT v.*, u.nombre AS usuario_nombre, r.nombre AS revisado_por
            FROM vacaciones v
            LEFT JOIN usuarios u ON v.usuario_id = u.id
            LEFT JOIN usuarios r ON v.revisado_por = r.id");
        $stmt->execute();
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
}