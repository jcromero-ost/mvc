<?php
require_once __DIR__ . '/Database.php';

class RegistroHorario
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function registrarEvento($userId, $tipoEvento)
    {
        try {
            $fechaHora = date('Y-m-d H:i:s');
            $stmt = $this->db->prepare("INSERT INTO registro_horarios (user_id, tipo_evento, fecha_hora) VALUES (?, ?, ?)");
            return $stmt->execute([$userId, $tipoEvento, $fechaHora]);
        } catch (PDOException $e) {
            error_log('Error al registrar evento: ' . $e->getMessage());
            return false;
        }
    }

    public function obtenerEstadoActual($userId)
    {
        $hoy = date('Y-m-d');

        $sql = "SELECT * FROM registro_horarios 
                WHERE user_id = ? 
                AND DATE(fecha_hora) = ?
                ORDER BY fecha_hora ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $hoy]);
        $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $estado = 'no_iniciado';
        $hora_inicio_jornada = null;
        $hora_inicio_descanso = null;
        $hora_fin_jornada = null;
        $segundos_trabajados = 0;

        $inicio_trabajo = null;

        foreach ($registros as $registro) {
            $tipo = $registro['tipo_evento'];
            $fecha = new DateTime($registro['fecha_hora']);

            switch ($tipo) {
                case 'inicio_jornada':
                    $estado = 'trabajando';
                    $hora_inicio_jornada = $registro['fecha_hora'];
                    $inicio_trabajo = $fecha;
                    break;

                case 'inicio_descanso':
                    $estado = 'descanso';
                    $hora_inicio_descanso = $registro['fecha_hora'];
                    if ($inicio_trabajo) {
                        $interval = $inicio_trabajo->diff($fecha);
                        $segundos_trabajados += ($interval->h * 3600) + ($interval->i * 60) + $interval->s;
                        $inicio_trabajo = null;
                    }
                    break;

                case 'fin_descanso':
                    $estado = 'trabajando';
                    $hora_inicio_descanso = null;
                    $inicio_trabajo = $fecha;
                    break;

                case 'fin_jornada':
                    $estado = 'finalizado';
                    $hora_fin_jornada = $registro['fecha_hora'];
                    if ($inicio_trabajo) {
                        $interval = $inicio_trabajo->diff($fecha);
                        $segundos_trabajados += ($interval->h * 3600) + ($interval->i * 60) + $interval->s;
                        $inicio_trabajo = null;
                    }
                    break;
            }
        }

        if ($estado === 'trabajando' && $inicio_trabajo) {
            $ahora = new DateTime();
            $interval = $inicio_trabajo->diff($ahora);
            $segundos_trabajados += ($interval->h * 3600) + ($interval->i * 60) + $interval->s;
        }

        return [
            'estado_actual' => $estado,
            'hora_inicio_jornada' => $hora_inicio_jornada,
            'hora_inicio_descanso' => $hora_inicio_descanso,
            'hora_fin_jornada' => $hora_fin_jornada,
            'segundos_trabajados' => $segundos_trabajados
        ];
    }

    public function buscarRegistros($usuario = null, $fechaDesde = null, $fechaHasta = null, $tipoEvento = null, $pagina = 1, $limite = 10)
    {
        $pagina = max(1, (int)$pagina);
        $limite = max(1, (int)$limite);
        $offset = ($pagina - 1) * $limite;

        $query = "SELECT 
                    DATE(rh.fecha_hora) AS fecha, 
                    TIME(rh.fecha_hora) AS hora, 
                    u.nombre AS usuario, 
                    rh.tipo_evento
                  FROM registro_horarios rh
                  INNER JOIN usuarios u ON rh.user_id = u.id
                  WHERE 1";

        $params = [];

        if (!empty($usuario)) {
            $query .= " AND rh.user_id = ?";
            $params[] = $usuario;
        }

        if (!empty($fechaDesde)) {
            $query .= " AND DATE(rh.fecha_hora) >= ?";
            $params[] = $fechaDesde;
        }

        if (!empty($fechaHasta)) {
            $query .= " AND DATE(rh.fecha_hora) <= ?";
            $params[] = $fechaHasta;
        }

        if (!empty($tipoEvento)) {
            $query .= " AND rh.tipo_evento = ?";
            $params[] = $tipoEvento;
        }

        $query .= " ORDER BY rh.fecha_hora DESC LIMIT $limite OFFSET $offset";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerJornadasFiltradas($usuario = null, $fechaDesde = null, $fechaHasta = null, $pagina = 1, $limite = 10) {
            $offset = ($pagina - 1) * $limite;

            $query = "SELECT
                        rh.user_id, 
                        u.nombre AS usuario, 
                        u.foto AS foto,
                        DATE(rh.fecha_hora) as fecha
                    FROM registro_horarios rh
                    INNER JOIN usuarios u ON rh.user_id = u.id
                    WHERE 1";

            $params = [];

            if (!empty($usuario)) {
                $query .= " AND rh.user_id = ?";
                $params[] = $usuario;
            }

            if (!empty($fechaDesde)) {
                $query .= " AND DATE(rh.fecha_hora) >= ?";
                $params[] = $fechaDesde;
            }

            if (!empty($fechaHasta)) {
                $query .= " AND DATE(rh.fecha_hora) <= ?";
                $params[] = $fechaHasta;
            }

            $query .= " GROUP BY rh.user_id, fecha
                        ORDER BY fecha DESC";

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);

            $jornadas = [];

            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $fila) {
                $userId = $fila['user_id'];
                $fecha = $fila['fecha'];
                $nombreUsuario = $fila['usuario'];
                $foto = $fila['foto'];

                // Obtener eventos ordenados cronológicamente por usuario y fecha
                $detalleQuery = "SELECT id, tipo_evento, fecha_hora 
                                FROM registro_horarios
                                WHERE user_id = ? AND DATE(fecha_hora) = ?
                                ORDER BY fecha_hora ASC";
                $detalleStmt = $this->db->prepare($detalleQuery);
                $detalleStmt->execute([$userId, $fecha]);
                $eventos = $detalleStmt->fetchAll(PDO::FETCH_ASSOC);

                $jornadaActual = null;
                $enDescanso = false;
                $inicioDescanso = null;

                foreach ($eventos as $evento) {
                    $idEvento = $evento['id'];
                    $tipo = $evento['tipo_evento'];
                    $hora = (new DateTime($evento['fecha_hora']))->format('H:i');

                    if ($tipo == 'inicio_jornada') {
                        $jornadaActual = [
                            'usuario' => $nombreUsuario,
                            'foto' => $foto,
                            'fecha' => $fecha,
                            'hora_inicio' => $hora,
                            'hora_fin' => null,
                            'id_inicio' => $idEvento,
                            'id_fin' => null,
                            'descansos' => []
                        ];
                    }

                    if ($tipo == 'inicio_descanso') {
                        $enDescanso = true;
                        $inicioDescanso = $hora;
                    }

                    if ($tipo == 'fin_descanso' && $enDescanso && $jornadaActual) {
                        $jornadaActual['descansos'][] = [
                            'inicio' => $inicioDescanso,
                            'fin' => $hora
                        ];
                        $enDescanso = false;
                        $inicioDescanso = null;
                    }

                    if ($tipo == 'fin_jornada' && $jornadaActual) {
                        $jornadaActual['hora_fin'] = $hora;
                        $jornadaActual['id_fin'] = $idEvento;
                        $jornadas[] = $jornadaActual;
                        $jornadaActual = null;
                    }
                }
            }

            // Calcular total y aplicar paginación manual
            $total = count($jornadas);
            $jornadasPaginadas = array_slice($jornadas, $offset, $limite);

            return [
                'jornadas' => $jornadasPaginadas,
                'total' => $total
            ];
        }

        public static function update($data) {
            $db = Database::connect();

            $sql_inicio = "UPDATE registro_horarios 
                        SET fecha_hora = :inicio, motivo_edicion = :motivo_inicio 
                        WHERE id = :id_inicio";

            $sql_fin = "UPDATE registro_horarios 
                        SET fecha_hora = :fin, motivo_edicion = :motivo_fin  
                        WHERE id = :id_fin";

            $stmt_inicio = $db->prepare($sql_inicio);
            $stmt_inicio->bindParam(':inicio', $data['hora_inicio']);
            $stmt_inicio->bindParam(':motivo_inicio', $data['motivo_inicio']);
            $stmt_inicio->bindParam(':id_inicio', $data['id_inicio']);

            $stmt_fin = $db->prepare($sql_fin);
            $stmt_fin->bindParam(':fin', $data['hora_fin']);
            $stmt_fin->bindParam(':motivo_fin', $data['motivo_fin']);
            $stmt_fin->bindParam(':id_fin', $data['id_fin']);

            $db->beginTransaction();

            try {
                $stmt_inicio->execute();
                $stmt_fin->execute();
                $db->commit();
                return true;
            } catch (PDOException $e) {
                $db->rollBack();
                return false;
            }
        }

} 
