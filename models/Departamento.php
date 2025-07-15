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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los departamentos sin webmaster
    public function getAllDepartamentosSinWebmaster() {
        $stmt = $this->db->prepare("SELECT * FROM departamentos WHERE id != 3 ORDER BY nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Obtener un departamento por ID
    public function getById($id) {
        $id = (int) $id;
        $stmt = $this->db->prepare("SELECT * FROM departamentos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear nuevo departamento
    public function create($nombre) {
        $stmt = $this->db->prepare("INSERT INTO departamentos (nombre) VALUES (:nombre)");
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Actualizar nombre del departamento
    public function update($id, $nombre) {
        $stmt = $this->db->prepare("UPDATE departamentos SET nombre = :nombre WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Eliminar departamento
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM departamentos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
