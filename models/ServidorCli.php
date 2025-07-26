<?php
require_once __DIR__ . '/Database.php';

class ServidorCli {
    public static function getByContratoId($contrato_id) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM contratos_clientes_servidores WHERE contrato_id = ?");
        $stmt->execute([$contrato_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO contratos_clientes_servidores (contrato_id, nombre, ip, descripcion) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['contrato_id'],
            $data['nombre'],
            $data['ip'],
            $data['descripcion']
        ]);
    }
}
