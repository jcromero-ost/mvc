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

        $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
        $stmt->bindParam(':alias', $data['alias'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(':telefono', $data['telefono'], PDO::PARAM_STR);
        $stmt->bindParam(':fecha_ingreso', $data['fecha_ingreso'], PDO::PARAM_STR);
        $stmt->bindParam(':password', $hash, PDO::PARAM_STR);
        $stmt->bindParam(':departamento_id', $data['departamento_id'], PDO::PARAM_INT);
        $stmt->bindParam(':activo', $data['activo'], PDO::PARAM_INT);
        $stmt->bindParam(':foto', $data['foto'], PDO::PARAM_STR);

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
        $stmt = $db->prepare("UPDATE usuarios SET nombre = :nombre, alias = :alias, email = :email, telefono = :telefono, fecha_ingreso = :fecha_ingreso, activo = :activo WHERE id = :id");
        return $stmt->execute([
            ':id' => $data['id'],
            ':nombre' => $data['nombre'],
            ':alias' => $data['alias'],
            ':email' => $data['email'],
            ':telefono' => $data['telefono'],
            ':fecha_ingreso' => $data['fecha_ingreso'],
            ':activo' => $data['activo']
        ]);
    }
    
    public static function delete($id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    
}
