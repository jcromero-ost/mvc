<?php
require_once(__DIR__ . '/DatabaseXGEST.php');

class Cliente {
    private $db;

    public function __construct() {
        $this->db = DatabaseXGEST::connect();
    }

    // Obtener todos los usuarios
    public function getAllClientes() {
        $stmt = $this->db->prepare("SELECT CCODCL, CNOM, CTEL1, CDNI, CMAIL1, CDOM, CPOB, CCODPO, CPAIS FROM fccli001");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear nuevo cliente
    public function create($data) {
        // Obtener el último ID existente
        $stmt = $this->db->query("SELECT MAX(CCODCL) AS max_id FROM fccli001");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $ultimoId = $result['max_id'];

        // Asegurarse de que sea un número, si no hay registros aún, iniciar en 1
        $nuevoId = is_numeric($ultimoId) ? (int)$ultimoId + 1 : 1;

        // Preparar la consulta de inserción
        $stmt = $this->db->prepare("
            INSERT INTO fccli001 
                (CCODCL, CNOM, CTEL1, CDNI, CMAIL1, CDOM, CPOB, CCODPO, CPAIS, CDOMFIS, CDOMENVFRA, CDOMENVMAT, COBS, COBSORD, CACCIONIS, CADMINIS)
            VALUES 
                (:id, :nombre, :telefono, :dni, :email, :direccion, :ciudad, :cp, :provincia, '', '', '', '', '', '', '')
        ");

        // Asignar el nuevo ID al array de datos
        $data['id'] = $nuevoId;

        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':dni', $data['dni']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':direccion', $data['direccion']);
        $stmt->bindParam(':ciudad', $data['ciudad']);
        $stmt->bindParam(':cp', $data['cp']);
        $stmt->bindParam(':provincia', $data['provincia']);

        return $stmt->execute();
    }
    

    // (Opcional) Obtener por ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM fccli001 WHERE CCODCL = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener el ID del cliente por su nombre
    public function getIdByCliente($nombreCliente) {
        $stmt = $this->db->prepare("SELECT CCODCL FROM fccli001 WHERE CNOM = :nombre");
        $stmt->bindParam(':nombre', $nombreCliente, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si se encuentra el cliente, devolver el ID
        return $result ? $result['CCODCL'] : null; // Retorna el ID o null si no se encuentra
    }
    
    public static function update($data) {
        $db = DatabaseXGEST::connect();

        $sql = "UPDATE fccli001 SET 
                    CNOM = :nombre,
                    CTEL1 = :telefono,
                    CDNI = :dni,
                    CMAIL1 = :email,
                    CDOM = :direccion,
                    CPOB = :ciudad,
                    CCODPO = :cp,
                    CPAIS = :provincia
                WHERE CCODCL = :id";
                
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $data['id']);
        $stmt->bindValue(':nombre', $data['nombre']);
        $stmt->bindValue(':telefono', $data['telefono']);
        $stmt->bindValue(':dni', $data['dni']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':direccion', $data['direccion']);
        $stmt->bindValue(':ciudad', $data['ciudad']);
        $stmt->bindValue(':cp', (int)$data['cp'], PDO::PARAM_INT);
        $stmt->bindValue(':provincia', $data['provincia']);

        return $stmt->execute();
    }


    
    public static function delete($id) {
        $db = DatabaseXGEST::connect();
        $stmt = $db->prepare("DELETE FROM fccli001 WHERE CCODCL = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
