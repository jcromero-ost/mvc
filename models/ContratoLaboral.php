<?php

require_once 'Database.php';

class ContratoLaboral
{
    public static function crear($data)
    {
        $db = Database::connect();

        try {
            // Desactivar contrato activo previo del usuario
            $desactivar = $db->prepare("UPDATE contratos_laborales SET activo = 0 WHERE usuario_id = ?");
            $desactivar->execute([$data['usuario_id']]);

            // Insertar nuevo contrato
            $sql = "INSERT INTO contratos_laborales (usuario_id, tipo, horas_diarias, fecha_inicio, fecha_fin, observaciones, activo)
                    VALUES (?, ?, ?, ?, ?, ?, 1)";
            $stmt = $db->prepare($sql);
            return $stmt->execute([
                $data['usuario_id'],
                $data['tipo'],
                $data['horas_diarias'],
                $data['fecha_inicio'],
                $data['fecha_fin'] ?: null,
                $data['observaciones']
            ]);
        } catch (PDOException $e) {
            error_log("Error al guardar contrato laboral: " . $e->getMessage());
            return false;
        }
    }
}
