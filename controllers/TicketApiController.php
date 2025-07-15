<?php 

public function storeAsignaciones($ticket_id, $tipo_asignacion, $valores) {
    $db = Database::connect();

    // Eliminar asignaciones anteriores
    $stmtDelete = $db->prepare("DELETE FROM ticket_asignaciones WHERE ticket_id = ?");
    $stmtDelete->execute([$ticket_id]);

    // Si no hay asignación, salir
    if ($tipo_asignacion === 'ninguno') return true;

    // Insertar nuevas asignaciones
    $stmtInsert = $db->prepare("INSERT INTO ticket_asignaciones (ticket_id, tipo_asignacion, valor_asignado) VALUES (?, ?, ?)");

    foreach ($valores as $valor) {
        $stmtInsert->execute([$ticket_id, $tipo_asignacion, $valor]);
    }

    return true;
}
