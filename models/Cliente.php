<?php
require_once(__DIR__ . '/../config/config_xgest.php');

class Cliente {

    // Obtener todos los clientes
    public static function getAllClientes() {
        global $pdo_xgest;
        $stmt = $pdo_xgest->query("
            SELECT CCODCL, CNOM, CTEL1, CDNI, CMAIL1, CDOM, CPOB, CCODPO, CPAIS 
            FROM fccli001
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener cliente por ID
    public static function getById($id) {
        global $pdo_xgest;
        $stmt = $pdo_xgest->prepare("SELECT * FROM fccli001 WHERE CCODCL = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener el ID del cliente por su nombre
    public static function getIdByCliente($nombreCliente) {
        global $pdo_xgest;
        $stmt = $pdo_xgest->prepare("SELECT CCODCL FROM fccli001 WHERE CNOM = ?");
        $stmt->execute([$nombreCliente]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['CCODCL'] : null;
    }

    // Crear nuevo cliente
    public static function create($data) {
        global $pdo_xgest;

        // Obtener último ID
        $stmt = $pdo_xgest->query("SELECT MAX(CCODCL) AS max_id FROM fccli001");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $nuevoId = is_numeric($result['max_id']) ? (int)$result['max_id'] + 1 : 1;

        // Insertar
        $stmt = $pdo_xgest->prepare("
            INSERT INTO fccli001 (
                CCODCL, CNOM, CTEL1, CDNI, CMAIL1, CDOM, CPOB, CCODPO, CPAIS,
                CDOMFIS, CDOMENVFRA, CDOMENVMAT, COBS, COBSORD, CACCIONIS, CADMINIS
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?,
                '', '', '', '', '', '', ''
            )
        ");

        return $stmt->execute([
            $nuevoId,
            $data['nombre'],
            $data['telefono'],
            $data['dni'],
            $data['email'],
            $data['direccion'],
            $data['ciudad'],
            $data['cp'],
            $data['provincia']
        ]);
    }

    // Actualizar cliente
    public static function update($data) {
        global $pdo_xgest;

        $stmt = $pdo_xgest->prepare("
            UPDATE fccli001 SET 
                CNOM = ?, CTEL1 = ?, CDNI = ?, CMAIL1 = ?, CDOM = ?,
                CPOB = ?, CCODPO = ?, CPAIS = ?
            WHERE CCODCL = ?
        ");

        return $stmt->execute([
            $data['nombre'],
            $data['telefono'],
            $data['dni'],
            $data['email'],
            $data['direccion'],
            $data['ciudad'],
            $data['cp'],
            $data['provincia'],
            $data['id']
        ]);
    }

    // Eliminar cliente
    public static function delete($id) {
        global $pdo_xgest;
        $stmt = $pdo_xgest->prepare("DELETE FROM fccli001 WHERE CCODCL = ?");
        return $stmt->execute([$id]);
    }
}
