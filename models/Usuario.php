<?php
require_once 'Database.php';

class Usuario {
    public static function all() {
        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($nombre, $email, $password) {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$nombre, $email, password_hash($password, PASSWORD_DEFAULT)]);
    }
}
