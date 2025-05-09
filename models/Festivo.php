<?php
require_once __DIR__ . '/Database.php';

class Festivo
{
    public static function allByYear($anio)
    {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM festivos WHERE YEAR(fecha) = ? ORDER BY fecha ASC");
        $stmt->execute([$anio]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($data)
    {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT INTO festivos (fecha, tipo, descripcion, comunidad_autonoma, municipio) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['fecha'],
            $data['tipo'],
            $data['descripcion'],
            $data['comunidad_autonoma'],
            $data['municipio']
        ]);
    }

    public static function delete($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM festivos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
