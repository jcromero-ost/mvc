<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Ticket.php';
require_once __DIR__ . '/../models/TicketAsignacion.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/MediosComunicacion.php';

class TicketController {

    public function ticketsPendientes() {
        require_once __DIR__ . '/../views/tickets_pendientes.php';
    }

    public function create() {
        require_once __DIR__ . '/../views/crear_ticket.php';
    }

    public function index() {
        require_once __DIR__ . '/../views/tickets.php';
    }

    public function edit() {
        require_once __DIR__ . '/../views/editar_ticket.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente_id = $_POST['cliente_id'] ?? null;
            $medio_id = $_POST['medio_comunicacion'] ?? null;
            $fecha_inicio = $_POST['fecha_inicio'] ?? date('Y-m-d H:i:s');
            $descripcion = $_POST['descripcion'] ?? '';
            $usuario_creador_id = $_SESSION['user']['id'] ?? null;

            $tipo_asignacion = $_POST['tipo_asignacion'] ?? 'ninguno';
            if ($tipo_asignacion === 'departamento') {
                $valores_asignacion = $_POST['departamentos'] ?? [];
            } elseif ($tipo_asignacion === 'tecnico') {
                $valores_asignacion = $_POST['tecnicos'] ?? [];
            } else {
                $valores_asignacion = [];
            }

            if (!$cliente_id || !$medio_id || !$descripcion || !$usuario_creador_id) {
                echo "Error: Faltan datos obligatorios.";
                return;
            }

            $ticket = new Ticket();
            $ticket_id = $ticket->create([
                'cliente_id' => $cliente_id,
                'medio_id' => $medio_id,
                'fecha_inicio' => $fecha_inicio,
                'descripcion' => $descripcion,
                'estado' => 'pendiente',
                'usuario_creador_id' => $usuario_creador_id,
            ], true); // true para devolver el ID

            if ($ticket_id) {
                $this->storeAsignaciones($ticket_id, $tipo_asignacion, $valores_asignacion);
                $_SESSION['success'] = "Ticket creado correctamente";
                header('Location: /tickets_pendientes');
                exit;
            } else {
                $_SESSION['error'] = "Error al guardar el ticket";
            }
        } else {
            echo "Acceso inválido.";
        }
    }

    public function storeAsignaciones($ticket_id, $tipo_asignacion, $valores) {
        $asignacionModel = new TicketAsignacion();
        return $asignacionModel->asignar($ticket_id, $tipo_asignacion, $valores);
    }

    public function storeAsignacionesNotificaciones() {
        if (!isset($_POST['id'], $_POST['tipo_asignacion'])) {
            die('Datos incompletos para asignar ticket.');
        }

        $ticket_id = $_POST['id'];
        $tipo = $_POST['tipo_asignacion'];
        $valores = [];

        if ($tipo === 'departamento' && isset($_POST['departamentos'])) {
            $valores = $_POST['departamentos'];
        } elseif ($tipo === 'tecnico' && isset($_POST['tecnicos'])) {
            $valores = $_POST['tecnicos'];
        }

        $asignacionModel = new TicketAsignacion();
        $exito = $asignacionModel->asignar($ticket_id, $tipo, $valores);

        if ($exito) {
            header("Location: /tickets_pendientes"); // Redirige a donde quieras
            exit;
        } else {
            die("Error al asignar ticket.");
        }
    }


    public function storeEdit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
               session_start();
            }
            $ticket_id = $_POST['id'] ?? null;
            $medio_id = $_POST['medio_comunicacion'] ?? null;
            $descripcion = $_POST['descripcion'] ?? null;
            $estado = $_POST['estado'] ?? null;

            if (!$ticket_id || !$medio_id || !$descripcion) {
                echo json_encode(['success' => false, 'error' => 'Faltan datos obligatorios.']);
                return;
            }

            $ticket = new Ticket();
            $resultado = $ticket->update([
                'id' => $ticket_id,
                'medio_id' => $medio_id,
                'descripcion' => $descripcion,
                'estado' => $estado
            ]);

            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Ticket actualizado correctamente.']);
            } else {
                echo json_encode(['success' => false, 'error' => 'No se pudo actualizar el ticket.']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Método inválido.']);
        }
    }

    public function updateEstado() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (!isset($data['id'], $data['estado'])) {
                echo json_encode(['success' => false, 'error' => 'Faltan datos']);
                return;
            }

            $ticket = new Ticket();
            $resultado = $ticket->updateEstado([
                'id' => $data['id'],
                'estado' => $data['estado'],
                'fecha_fin' => date('Y-m-d H:i:s')
            ]);

            echo json_encode(['success' => $resultado]);
        }
    }

    public function storeComentario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticket_id = $_POST['id'] ?? null;
            $fecha = $_POST['fecha'] ?? null;
            $tipo = $_POST['tipo'] ?? 'normal';
            $contenido = $_POST['contenido'] ?? '';
            $usuario_id = $_SESSION['user']['id'] ?? null;

            if (!$ticket_id || !$contenido || !$usuario_id) {
                echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
                return;
            }

            $ticket = new Ticket();
            $resultado = $ticket->createComentario([
                'ticket_id' => $ticket_id,
                'fecha' => $fecha,
                'contenido' => $contenido,
                'tipo' => $tipo
            ]);

            echo json_encode(['success' => $resultado]);
        }
    }

    public function getComentarios() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticket_id = $_POST['id'] ?? null;
            if (!$ticket_id) {
                echo json_encode(['success' => false, 'error' => 'Falta ID']);
                return;
            }

            $ticket = new Ticket();
            $comentarios = $ticket->getAllComentarios($ticket_id);

            echo json_encode(['success' => true, 'comentarios' => $comentarios]);
        }
    }

    public function updateComentario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comentario_id = $_POST['id'] ?? null;
            $contenido = $_POST['contenido'] ?? null;
            $hora_fin = $_POST['hora_fin'] ?? null;

            if (!$comentario_id || $contenido === null) {
                echo json_encode(['success' => false, 'error' => 'Faltan datos']);
                return;
            }

            $ticket = new Ticket();
            $data = [
                'id' => $comentario_id,
                'contenido' => $contenido
            ];

            if (!empty($hora_fin)) {
                $data['hora_fin'] = $hora_fin;
            }

            $resultado = $ticket->updateComentarios($data);

            echo json_encode(['success' => $resultado]);
        }
    }


    public function deleteComentario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comentario_id = $_POST['id'] ?? null;

            if (!$comentario_id) {
                echo json_encode(['success' => false, 'error' => 'Falta ID del comentario']);
                return;
            }

            $ticket = new Ticket();
            $resultado = $ticket->deleteComentarios($comentario_id);

            echo json_encode(['success' => $resultado]);
        }
    }
    /*_____NO SE USARA POR AHORA_____*/
    /*public function storeCronometro() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Acceso inválido.']);
            exit;
        }

        $ticket_id = $_POST['ticket_id'] ?? null;
        $hora_inicio = $_POST['hora_inicio'] ?? null;
        $hora_fin = $_POST['hora_fin'] ?? null;
        $tiempo = $_POST['tiempo'] ?? null;
        $usuario_id = $_SESSION['user']['id'] ?? null;

        if (!$ticket_id || !$hora_inicio || !$hora_fin || !$tiempo || !$usuario_id) {
            echo json_encode(['error' => 'Faltan datos obligatorios.']);
            exit;
        }

        $ticket = new Ticket();
        $resultado = $ticket->createCronometro([
            'ticket_id' => $ticket_id,
            'fecha' => date('Y-m-d'),
            'hora_inicio' => $hora_inicio,
            'hora_fin' => $hora_fin,
            'tiempo' => $tiempo,
            'usuario_id' => $usuario_id
        ]);

        echo json_encode(['success' => $resultado]);
    }*/


    public function storeComentarioSoloFecha() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Acceso inválido.']);
            exit;
        }

        $ticket_id = $_POST['ticket_id'] ?? null;
        $hora_inicio = $_POST['hora_inicio'] ?? null;
        $usuario_id = $_SESSION['user']['id'] ?? null;

        if (!$ticket_id || !$hora_inicio || !$usuario_id) {
            echo json_encode(['error' => 'Faltan datos obligatorios.']);
            exit;
        }

        $ticket = new Ticket();
        $id = $ticket->createComentarioSoloFecha([
            'ticket_id' => $ticket_id,
            'fecha' => date('Y-m-d'),
            'hora_inicio' => $hora_inicio,
            'usuario_id' => $usuario_id
        ]);

        if ($id) {
            echo json_encode(['status' => 'ok', 'id' => $id]);
        } else {
            echo json_encode(['error' => 'Error al guardar el comentario.']);
        }
    }



    

    public function storeUpdateComentarioSoloFecha() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Acceso inválido.']);
            exit;
        }

        $contenido = $_POST['contenido'] ?? null;
        $hora_fin = $_POST['hora_fin'] ?? null;
        $id = $_POST['id'] ?? null;

        if (!$id || !$hora_fin) {
            echo json_encode(['error' => 'Faltan datos obligatorios.']);
            exit;
        }

        $comentario = new Ticket();
        $resultado = $comentario->updateComentarioSoloFecha([
            'contenido' => $contenido,
            'hora_fin' => $hora_fin,
            'id' => $id
        ]);

        if ($resultado) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Error al guardar el comentario.']);
        }
    }


    public function ObtenerTicketsPorUsuario() {
    header('Content-Type: application/json');

    if (!isset($_SESSION['user']['id'])) {
        echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
        exit;
    }

    $ticket = new Ticket();
    $tickets = $ticket->getTicketNoAsignados();

    echo json_encode(['success' => true, 'data' => $tickets]);
}


    public function getTiempoTotal() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticket_id = $_POST['id'] ?? null;
            $ticket = new Ticket();
            $tiempo = $ticket->getTiempoTotal($ticket_id);
            echo json_encode($tiempo);
        }
    }


}

if (isset($_POST['accion'])) {
    $controller = new TicketController();
    switch ($_POST['accion']) {
        case 'eliminar':
            $controller->delete();
            break;
    }
}
