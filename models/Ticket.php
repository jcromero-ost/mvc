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

    // Crear nuevo usuario
    public function create($data) {
        // Sacar el id del usuario logueado
        $usuario_creador = $_SESSION['id'] ?? null;

        $stmt = $this->db->prepare("
            INSERT INTO tickets 
                (medio_id, cliente_nombre, tecnico_id, fecha_inicio, descripcion, usuario_creador_id)
            VALUES 
                (:medio_comunicacion, :cliente, :tecnico, :fecha_inicio, :descripcion, :usuario_creador_id)
        ");
    
        $stmt->bindParam(':medio_comunicacion', $data['medio_comunicacion']);
        $stmt->bindParam(':cliente', $data['cliente']);
        $stmt->bindParam(':tecnico', $data['tecnico']);
        $stmt->bindParam(':fecha_inicio', $data['fecha_inicio']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':usuario_creador_id', $usuario_creador);
    
        return $stmt->execute();
    }
        
    public static function update($data) {
        $db = Database::connect();
    
        $sql = "UPDATE usuarios SET 
                    nombre = :nombre,
                    alias = :alias,
                    email = :email,
                    telefono = :telefono,
                    fecha_ingreso = :fecha_ingreso,
                    departamento_id = :departamento_id,
                    activo = :activo";
    
        if (!empty($data['foto'])) {
            $sql .= ", foto = :foto";
        }
    
        $sql .= " WHERE id = :id";
    
        $stmt = $db->prepare($sql);
    
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':alias', $data['alias']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':fecha_ingreso', $data['fecha_ingreso']);
        $stmt->bindParam(':departamento_id', $data['departamento_id']);
        $stmt->bindParam(':activo', $data['activo']);
    
        if (!empty($data['foto'])) {
            $stmt->bindParam(':foto', $data['foto']);
        }
    
        return $stmt->execute();
    }
    
    
    public static function delete($id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM tickets WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
