<?php
require_once(__DIR__ . '/Database.php');

class TicketAsignacion {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAllAsignaciones() {
        $stmt = $this->db->prepare("SELECT * FROM ticket_asignaciones ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function asignar($ticket_id, $tipo, $valores = []) {
        $this->borrarAsignaciones($ticket_id);

        if ($tipo === 'ninguno' || empty($valores)) return true;

        $sql = "INSERT INTO ticket_asignaciones (ticket_id, tipo, ref_id) 
                VALUES (:ticket_id, :tipo, :ref_id)";
        $stmt = $this->db->prepare($sql);

        foreach ($valores as $valor) {
            $stmt->execute([
                ':ticket_id' => $ticket_id,
                ':tipo' => $tipo,
                ':ref_id' => $valor
            ]);
        }

        return true;
    }

    public function borrarAsignaciones($ticket_id) {
        $stmt = $this->db->prepare("DELETE FROM ticket_asignaciones WHERE ticket_id = ?");
        return $stmt->execute([$ticket_id]);
    }

    public function obtenerAsignaciones($ticket_id) {
        $stmt = $this->db->prepare("SELECT * FROM ticket_asignaciones WHERE ticket_id = ?");
        $stmt->execute([$ticket_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
