<?php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Xgest.php';

class ContratoCli
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    /**
     * Crea un nuevo contrato con múltiples servicios asociados
     */
    public function create($cliente_id, $fecha_alta, $usuario_id, $servicios = [])
    {
        try {
            $this->conn->beginTransaction();

            // Insertar contrato en la tabla contratos_clientes
            $sql = "INSERT INTO contratos_clientes (cliente_id, usuario_id, fecha_alta)
                    VALUES (:cliente_id, :usuario_id, :fecha_alta)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':cliente_id' => $cliente_id,
                ':usuario_id' => $usuario_id,
                ':fecha_alta' => $fecha_alta
            ]);

            $contrato_id = $this->conn->lastInsertId();

            // Insertar servicios si vienen correctamente
            if (!empty($servicios) && is_array($servicios)) {

                $sql_serv = "INSERT INTO contratos_clientes_servicios (contrato_id, servicio)
                            VALUES (:contrato_id, :servicio)";
                $stmt_serv = $this->conn->prepare($sql_serv);

                foreach ($servicios as $servicio) {
                    if (!empty($servicio)) {
                        $stmt_serv->execute([
                            ':contrato_id' => $contrato_id,
                            ':servicio' => $servicio
                        ]);
                    }
                }
            }

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error al crear contrato: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener todos los contratos con servicios
     */
    public function getAll()
    {
        $sql = "SELECT c.id, c.fecha_alta, cl.nombre AS cliente, GROUP_CONCAT(cs.servicio) AS servicios
                FROM contratos_clientes c
                INNER JOIN clientes cl ON cl.id = c.cliente_id
                LEFT JOIN contratos_clientes_servicios cs ON cs.contrato_id = c.id
                GROUP BY c.id
                ORDER BY c.fecha_alta DESC";

        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener contratos de un cliente por ID
     */
    public function getByClienteId($cliente_id)
    {
        $sql = "SELECT c.id, c.fecha_alta, GROUP_CONCAT(cs.servicio) AS servicios
                FROM contratos_clientes c
                LEFT JOIN contratos_clientes_servicios cs ON cs.contrato_id = c.id
                WHERE c.cliente_id = :cliente_id
                GROUP BY c.id
                ORDER BY c.fecha_alta DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':cliente_id' => $cliente_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

 
    public static function getAllWithServicios()
    {
        $db = Database::connect();

        // Obtener todos los contratos
        $stmt = $db->prepare("SELECT * FROM contratos_clientes");
        $stmt->execute();
        $contratos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$contratos) return [];

        // Obtener todos los servicios
        $stmtServicios = $db->prepare("SELECT contrato_id, servicio FROM contratos_clientes_servicios");
        $stmtServicios->execute();
        $serviciosRaw = $stmtServicios->fetchAll(PDO::FETCH_ASSOC);

        // Agrupar servicios por contrato
        $serviciosPorContrato = [];
        foreach ($serviciosRaw as $s) {
            $serviciosPorContrato[$s['contrato_id']][] = ['nombre' => $s['servicio']];
        }

        // Asociar servicios y nombre de cliente
        foreach ($contratos as &$contrato) {
            $id = $contrato['id'];
            $clienteId = $contrato['cliente_id'];

            // Agregar servicios al contrato
            $contrato['servicios'] = $serviciosPorContrato[$id] ?? [];

            // Obtener nombre del cliente desde XGEST
            $cliente = Xgest::getClientePorId($clienteId);
            $contrato['cliente_nombre'] = $cliente['nombre'] ?? "Cliente #$clienteId";
        }

        return $contratos;
    }


}
