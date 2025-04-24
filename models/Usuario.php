<?php
require_once(__DIR__ . '/Database.php');

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::connect(); // Usamos el método estático
    }

    public function getAllUsuarios() {
        $stmt = $this->db->prepare("SELECT * FROM usuarios");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function create($data) {
        $db = Database::connect();
        $hash = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt = $db->prepare("
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
}
