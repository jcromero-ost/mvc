<?php
require_once(__DIR__ . '/Database.php');

class MedioComunicacion {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    // Obtener todos los medios
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM medios_comunicacion ORDER BY nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un medio por ID
    public function getById($id) {
        $id = (int) $id;
        $stmt = $this->db->prepare("SELECT * FROM medios_comunicacion WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear nuevo medio
    public function create($nombre) {
        $stmt = $this->db->prepare("INSERT INTO medios_comunicacion (nombre) VALUES (:nombre)");
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Actualizar nombre de medio
    public function update($id, $nombre) {
        $stmt = $this->db->prepare("UPDATE medios_comunicacion SET nombre = :nombre WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Eliminar medios
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM medios_comunicacion WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
