<?php
require_once(__DIR__ . '/Database.php');

class Departamento {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    // Obtener todos los departamentos
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM departamentos ORDER BY nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener un departamento por ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM departamentos WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Crear nuevo departamento
    public function create($nombre) {
        $stmt = $this->db->prepare("INSERT INTO departamentos (nombre) VALUES (:nombre)");
        $stmt->bindParam(':nombre', $nombre);
        return $stmt->execute();
    }

    // Actualizar nombre del departamento
    public function update($id, $nombre) {
        $stmt = $this->db->prepare("UPDATE departamentos SET nombre = :nombre WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nombre', $nombre);
        return $stmt->execute();
    }

    // Eliminar departamento
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM departamentos WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
