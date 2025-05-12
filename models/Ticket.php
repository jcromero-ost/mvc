<?php
require_once(__DIR__ . '/Database.php');

class Ticket {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    // Obtener todos los tickets
    public function getAllTickets() {
        $stmt = $this->db->prepare("SELECT * FROM tickets ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los tickets no finalizados
    public function getAllTicketsNoFinalizados() {
        $stmt = $this->db->prepare("SELECT * FROM tickets WHERE estado != 'finalizado' ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // (Opcional) Obtener por ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tickets WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear nuevo ticket
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO tickets 
                (medio_id, cliente_id, tecnico_id, fecha_inicio, descripcion, usuario_creador_id)
            VALUES 
                (:medio_comunicacion, :cliente, :tecnico, :fecha_inicio, :descripcion, :usuario_creador_id)
        ");
    
        $stmt->bindParam(':medio_comunicacion', $data['medio_comunicacion']);
        $stmt->bindParam(':cliente', $data['cliente']);
        $stmt->bindParam(':tecnico', $data['tecnico']);
        $stmt->bindParam(':fecha_inicio', $data['fecha_inicio']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':usuario_creador_id', $data['usuario_creador']);
    
        return $stmt->execute();
    }
        
    public static function update($data) {
        $db = Database::connect();
    
        $sql = "UPDATE tickets SET 
                    medio_id = :medio_comunicacion,
                    cliente_id = :cliente,
                    tecnico_id = :tecnico,
                    descripcion = :descripcion
                WHERE id = :id";
    
        $stmt = $db->prepare($sql);
    
        $stmt->bindParam(':medio_comunicacion', $data['medio_comunicacion']);
        $stmt->bindParam(':cliente', $data['cliente']);
        $stmt->bindParam(':tecnico', $data['tecnico']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':id', $data['id']);
    
        return $stmt->execute();
    }
    
    
    public static function delete($id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM tickets WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Crear nuevo comentario
    public function createComentario($data) {
        $stmt = $this->db->prepare("
            INSERT INTO comentarios 
                (ticket_id, fecha_hora, contenido, tipo)
            VALUES 
                (:ticket_id, :fecha_hora, :contenido, :tipo)
        ");
    
        $stmt->bindParam(':ticket_id', $data['ticket_id']);
        $stmt->bindParam(':fecha_hora', $data['fecha_hora']);
        $stmt->bindParam(':contenido', $data['contenido']);
        $stmt->bindParam(':tipo', $data['tipo']);    
    
        return $stmt->execute();
    }

    // Obtener comentarios por id
    public function getAllComentarios($ticket_id) {
        $stmt = $this->db->prepare("SELECT * FROM comentarios WHERE ticket_id = :ticket_id ORDER BY fecha_hora DESC");
        $stmt->bindParam(':ticket_id', $ticket_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Borrar comentarios
    public static function deleteComentarios($id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM comentarios WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Actualizar comentarios
    public function updateComentarios($data) {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE comentarios SET contenido = :contenido WHERE id = :id");
        $stmt->bindParam(':contenido', $data['contenido']);
        $stmt->bindParam(':id', $data['id']);
        return $stmt->execute();
    }

    // Actualizar estado ticket
    public function updateEstado($data) {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE tickets SET estado = :estado WHERE id = :id");
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':id', $data['id']);
        return $stmt->execute();
    }
}
