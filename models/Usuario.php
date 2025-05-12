<?php
require_once(__DIR__ . '/Database.php');

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    // Obtener todos los usuarios
    public function getAllUsuarios() {
        $stmt = $this->db->prepare("SELECT * FROM usuarios ORDER BY nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear nuevo usuario
    public function create($data) {
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);
    
        $stmt = $this->db->prepare("
            INSERT INTO usuarios 
                (nombre, alias, email, telefono, fecha_ingreso, password, departamento_id, activo, foto)
            VALUES 
                (:nombre, :alias, :email, :telefono, :fecha_ingreso, :password, :departamento_id, :activo, :foto)
        ");
    
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':alias', $data['alias']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':fecha_ingreso', $data['fecha_ingreso']);
        $stmt->bindParam(':password', $hash);
        $stmt->bindParam(':departamento_id', $data['departamento_id']);
        $stmt->bindParam(':activo', $data['activo']);
        $stmt->bindParam(':foto', $data['foto']);
    
        return $stmt->execute();
    }
    

    // (Opcional) Buscar por email
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // (Opcional) Obtener por ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // (Opcional) Actualizar usuario
    public static function all() {
        $db = Database::connect();
        $sql = "SELECT usuarios.*, departamentos.nombre AS nombre_departamento 
                FROM usuarios 
                LEFT JOIN departamentos ON usuarios.departamento_id = departamentos.id";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        $stmt = $db->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function obtenerUsuariosActivos()
{
    $conexion = Database::connect();
    $query = "SELECT id, nombre FROM usuarios WHERE activo = 1 ORDER BY nombre ASC";
    //                                  🔵 Correcto campo usado: activo

    $stmt = $conexion->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    
    
    
}