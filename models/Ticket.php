<?php
require_once(__DIR__ . '/Database.php');
require_once(__DIR__ . '/DatabaseXGEST.php');

class Ticket {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    // Obtener todos los tickets
    public function getAllTickets() {
        $stmt = $this->db->prepare("SELECT * FROM tickets ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todos los tickets no finalizados
    public function getAllTicketsNoFinalizados() {
        $stmt = $this->db->prepare("SELECT * FROM tickets WHERE estado != 'finalizado' ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // (Opcional) Obtener por ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tickets WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener por ID de cliente
    public function getByIdCliente($cliente_id) {
        $stmt = $this->db->prepare("SELECT * FROM tickets WHERE cliente_id = :cliente_id");
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear nuevo ticket
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO tickets 
                (medio_id, cliente_id, tecnico_id, fecha_inicio, descripcion, usuario_creador_id)
            VALUES 
                (:medio_comunicacion, :cliente, :tecnico, :fecha_inicio, :descripcion, :usuario_creador_id)
        ");
    
        $stmt->bindParam(':medio_comunicacion', $data['medio_comunicacion']);
        $stmt->bindParam(':cliente', $data['cliente']);
        $stmt->bindParam(':tecnico', $data['tecnico']);
        $stmt->bindParam(':fecha_inicio', $data['fecha_inicio']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':usuario_creador_id', $data['usuario_creador']);
    
        return $stmt->execute();
    }
        
    public static function update($data) {
        $db = Database::connect();
    
        $sql = "UPDATE tickets SET 
                    medio_id = :medio_comunicacion,
                    tecnico_id = :tecnico,
                    descripcion = :descripcion
                WHERE id = :id";
    
        $stmt = $db->prepare($sql);
    
        $stmt->bindParam(':medio_comunicacion', $data['medio_comunicacion']);
        $stmt->bindParam(':tecnico', $data['tecnico']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':id', $data['id']);
    
        return $stmt->execute();
    }
    
    
    public static function delete($id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM tickets WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Crear nuevo comentario
    public function createComentario($data) {
        $stmt = $this->db->prepare("
            INSERT INTO comentarios 
                (ticket_id, fecha_hora, contenido, tipo)
            VALUES 
                (:ticket_id, :fecha_hora, :contenido, :tipo)
        ");
    
        $stmt->bindParam(':ticket_id', $data['ticket_id']);
        $stmt->bindParam(':fecha_hora', $data['fecha_hora']);
        $stmt->bindParam(':contenido', $data['contenido']);
        $stmt->bindParam(':tipo', $data['tipo']);    
    
        return $stmt->execute();
    }

    // Obtener comentarios por id
    public function getAllComentarios($ticket_id) {
        $stmt = $this->db->prepare("SELECT * FROM comentarios WHERE ticket_id = :ticket_id ORDER BY fecha_hora DESC");
        $stmt->bindParam(':ticket_id', $ticket_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Borrar comentarios
    public static function deleteComentarios($id) {
        $db = Database::connect();
        $stmt = $db->prepare("DELETE FROM comentarios WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Actualizar comentarios
    public function updateComentarios($data) {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE comentarios SET contenido = :contenido WHERE id = :id");
        $stmt->bindParam(':contenido', $data['contenido']);
        $stmt->bindParam(':id', $data['id']);
        return $stmt->execute();
    }

    // Actualizar estado ticket
    public function updateEstado($data) {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE tickets SET estado = :estado WHERE id = :id");
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':id', $data['id']);
        return $stmt->execute();
    }

    // Crear nuevo tiempo
    public function createCronometro($data) {
        $stmt = $this->db->prepare("
            INSERT INTO cronometro 
                (ticket_id, fecha, tiempo, usuario_id, hora_inicio, hora_fin)
            VALUES 
                (:ticket_id, :fecha, :tiempo, :usuario_id, :hora_inicio, :hora_fin)
        ");
    
        $stmt->bindParam(':ticket_id', $data['ticket_id']);
        $stmt->bindParam(':fecha', $data['fecha']);
        $stmt->bindParam(':tiempo', $data['tiempo']);
        $stmt->bindParam(':usuario_id', $data['usuario_id']);
        $stmt->bindParam(':hora_inicio', $data['hora_inicio']);
        $stmt->bindParam(':hora_fin', $data['hora_fin']);
    
    
        return $stmt->execute();
    }

    // Obtener cronometro por id de ticket
    public function getAllCronometro($ticket_id) {
        $stmt = $this->db->prepare("
            SELECT c.tiempo, u.nombre as nombre_usuario
            FROM cronometro c
            JOIN usuarios u ON c.usuario_id = u.id
            WHERE c.ticket_id = :ticket_id
        ");
        $stmt->bindParam(':ticket_id', $ticket_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener cronometro por id de ticket
    public function getTiempoTotal($ticket_id) {
        $stmt = $this->db->prepare("
            SELECT SUM(tiempo) AS tiempo_total
            FROM cronometro
            WHERE ticket_id = :ticket_id
        ");
        $stmt->bindParam(':ticket_id', $ticket_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // <- cambio aquí
    }

    // Actualizar tecnico
    public function updateTecnico($data) {
        $db = Database::connect();
        $stmt = $db->prepare("UPDATE tickets SET tecnico_id = :tecnico_id WHERE id = :id");
        $stmt->bindParam(':tecnico_id', $data['tecnico_id']);
        $stmt->bindParam(':id', $data['id']);
        return $stmt->execute();
    }

    public function createAlbaran($data) {
        $db = DatabaseXGEST::connect();

        $stmt = $db->query("SELECT MAX(LALBA) AS max_id FROM fclia001");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $ultimoId = $result['max_id'];
        $nuevoId = is_numeric($ultimoId) ? (int)$ultimoId + 1 : 1;

        $stmt = $db->prepare("
            INSERT INTO fclia001 (
                LALBA, LLINEA, LALMACEN, LCODCL, LFECHA, LITEM, LCODAR, LDIFPREC, LCANTI, LPRECI,
                LNUMPED, LLINPED, CALIDA, LMARCAS, LMEDIDAS, LAMPDES, LOBSINT
            ) VALUES (
                :id, 1, 0, :cliente, :fecha, '', :codigo, 'N', :cantidad, :precio,
                0, 0, '', '', '', :descripcion, ''
            )
        ");

        $stmt->bindValue(':id', $nuevoId);
        $stmt->bindValue(':cliente', $data['cliente_albaranar']);
        $stmt->bindValue(':fecha', $data['fecha_albaranar']);
        $stmt->bindValue(':codigo', $data['codigo_articulo_albaranar']);
        $stmt->bindValue(':cantidad', $data['cantidad_albaranar']);
        $stmt->bindValue(':precio', $data['precio_albaranar']);
        $stmt->bindValue(':descripcion', $data['descripcion_amplia_albaranar']);

        return $stmt->execute();
    }
}
