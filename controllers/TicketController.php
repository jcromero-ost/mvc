<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Ticket.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/MediosComunicacion.php';

class TicketController {

    public function create() {
        $mediosModel = new MedioComunicacion();
        $medios_comunicacion = $mediosModel->getAll();
        require_once __DIR__ . '/../views/crear_ticket.php';
    }

    public function ticketsPendientes() {
        require_once __DIR__ . '/../views/tickets_pendientes.php';
    }

    public function index() {
        require_once __DIR__ . '/../views/tickets.php';
    }

    public function edit() {
        require_once __DIR__ . '/../views/editar_ticket.php';
    }
    
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                Ticket::delete($id);
                $_SESSION['mensaje'] = "Ticket eliminado correctamente.";
                header("Location: ../views/tickets.php");
                exit;

            } else {
                
                echo "ID no válido para eliminar.";
            }
        }
    }

    public function store() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $medio_comunicacion = $_POST['medio_comunicacion'] ?? '';
            $nombreCliente = $_POST['cliente'] ?? '';
            $tecnico = $_POST['tecnico'] ?? '';
            $fecha_inicio = $_POST['fecha_inicio'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';

            // Sacar el id del usuario logueado
            $usuario_creador_completo = $_SESSION['user'] ?? null;

            // Accede a la propiedad 'id' directamente
            $usuario_creador = $usuario_creador_completo['id'] ?? null; // Si 'id' no está definida, será null

            $cliente = new Cliente();
            // Obtener el ID del cliente
            $idCliente = $cliente->getIdByCliente($nombreCliente);

            // Validaciones mínimas
            if (empty($medio_comunicacion) || empty($nombreCliente) || empty($tecnico) || empty($fecha_inicio) || empty($descripcion)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios.';
                header('Location: /crear_ticket');
                exit;
            }

            $ticket = new Ticket();
            $ticket->create([
                'medio_comunicacion' => $medio_comunicacion,
                'cliente' => $idCliente,
                'tecnico' => $tecnico,
                'fecha_inicio' => $fecha_inicio,
                'descripcion' => $descripcion,
                'usuario_creador' => $usuario_creador
            ]);


            $_SESSION['success'] = 'Ticket creado correctamente.';
            header('Location: /tickets_pendientes');
            exit;
        }

        // Si entra por GET o sin POST válido, redirigir
        header('Location: /tickets_pendientes');
        exit;
    }
    
    // Actualizar comentarios
    public function storeEdit() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $medio_comunicacion = $_POST['medio_comunicacion'] ?? '';
            $tecnico = $_POST['tecnico'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $id = $_POST['id'] ?? '';

            // Validaciones mínimas
            if (empty($medio_comunicacion) || empty($tecnico) || empty($descripcion)) {
                // Enviar error como respuesta JSON
                echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
                exit;  // Detener la ejecución
            }

            $ticket = new Ticket();
            $exito = $ticket->update([
                'medio_comunicacion' => $medio_comunicacion,
                'tecnico' => $tecnico,
                'descripcion' => $descripcion,
                'id' => $id
            ]);
    
            if ($exito) {
                $this->responderJson(['success' => true]);
            } else {
                $this->responderJson(['success' => false, 'error' => 'Error al guardar en base de datos.']);
            }
            return;
        }
    
        $this->responderJson(['success' => false, 'error' => 'Método inválido.']);
    }

    public function storeComentarios() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticket_id = $_POST['id'] ?? '';
            $fecha_hora = $_POST['fecha_hora'] ?? '';
            $contenido = $_POST['contenido'] ?? '';
            $tipo = $_POST['tipo'] ?? '';
    
            if (empty($ticket_id) || empty($fecha_hora) || empty($contenido)) {
                $this->responderJson(['success' => false, 'error' => 'El comentario no puede estar vacío']);
                return;
            }
    
            $ticket = new Ticket();
            $exito = $ticket->createComentario([
                'ticket_id' => $ticket_id,
                'fecha_hora' => $fecha_hora,
                'contenido' => $contenido,
                'tipo' => $tipo
            ]);
    
            if ($exito) {
                $this->responderJson(['success' => true]);
            } else {
                $this->responderJson(['success' => false, 'error' => 'Error al guardar en base de datos.']);
            }
            return;
        }
    
        $this->responderJson(['success' => false, 'error' => 'Método inválido.']);
    }
    
    private function responderJson($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function obtenerComentariosPorTicket() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticket_id = $_POST['id'] ?? ''; // Asegúrate de que 'id' esté correctamente enviado

            // Obtener los comentarios por ticket_id
            $ticket = new Ticket();
            $comentarios = $ticket->getAllComentarios($ticket_id);

            if ($comentarios) {
                $this->responderJson(['success' => true, 'comentarios' => $comentarios]);
            } else {
                $this->responderJson(['success' => false, 'error' => 'No se encontraron comentarios.']);
            }
            return;
        }

        $this->responderJson(['success' => false, 'error' => 'Método inválido.']);
    }

    // Borrar comentarios
    public function deleteComentarios() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticket_id = $_POST['id'] ?? '';

            $ticket = new Ticket();
            $exito = $ticket->deleteComentarios($ticket_id);

    
            if ($exito) {
                $this->responderJson(['success' => true]);
            } else {
                $this->responderJson(['success' => false, 'error' => 'Error al guardar en base de datos.']);
            }
            return;
        }
    
        $this->responderJson(['success' => false, 'error' => 'Método inválido.']);
    }

    // Actualizar comentarios
    public function updateComentarios() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $contenido = $_POST['contenido'] ?? '';

            $ticket = new Ticket();
            $exito = $ticket->updateComentarios([
                'contenido' => $contenido,
                'id' => $id
            ]);
    
            if ($exito) {
                $this->responderJson(['success' => true]);
            } else {
                $this->responderJson(['success' => false, 'error' => 'Error al guardar en base de datos.']);
            }
            return;
        }
    
        $this->responderJson(['success' => false, 'error' => 'Método inválido.']);
    }

    // Actualizar estado ticket
    public function updateEstado() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $estado = $_POST['estado'] ?? '';

            $ticket = new Ticket();
            $exito = $ticket->updateEstado([
                'estado' => $estado,
                'id' => $id
            ]);
    
            if ($exito) {
                $this->responderJson(['success' => true]);
            } else {
                $this->responderJson(['success' => false, 'error' => 'Error al guardar en base de datos.']);
            }
            return;
        }
    
        $this->responderJson(['success' => false, 'error' => 'Método inválido.']);
    }

    // Crear cronometro
    public function storeCronometro() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticket_id = $_POST['ticket_id'] ?? '';
            $fecha = $_POST['fecha'] ?? '';
            $tiempo = $_POST['tiempo'] ?? '';
            $usuario_id = $_SESSION['user']['id'];
            $hora_inicio = $_POST['hora_inicio'] ?? '';
            $hora_fin = $_POST['hora_fin'] ?? '';
    
            $ticket = new Ticket();
            $exito = $ticket->createCronometro([
                'ticket_id' => $ticket_id,
                'fecha' => $fecha,
                'tiempo' => $tiempo,
                'usuario_id' => $usuario_id,
                'hora_inicio' => $hora_inicio,
                'hora_fin' => $hora_fin
            ]);
    
            if ($exito) {
                $this->responderJson(['success' => true]);
            } else {
                $this->responderJson(['success' => false, 'error' => 'Error al guardar en base de datos.']);
            }
            return;
        }
    
        $this->responderJson(['success' => false, 'error' => 'Método inválido.']);
    }

    public function obtenerCronometroPorTicket() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticket_id = $_POST['id'] ?? ''; // Asegúrate de que 'id' esté correctamente enviado

            // Obtener los comentarios por ticket_id
            $ticket = new Ticket();
            $cronometro_registros = $ticket->getAllCronometro($ticket_id);

            if ($cronometro_registros) {
                $this->responderJson(['success' => true, 'cronometro_registros' => $cronometro_registros]);
            } else {
                $this->responderJson(['success' => false, 'error' => 'No se encontraron registros de tiempo.']);
            }
            return;
        }

        $this->responderJson(['success' => false, 'error' => 'Método inválido.']);
    }

    // Actualizar estado ticket
    public function updateTecnico() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $tecnico_id = $_POST['tecnico_id'] ?? '';

            $ticket = new Ticket();
            $exito = $ticket->updateTecnico([
                'tecnico_id' => $tecnico_id,
                'id' => $id
            ]);
    
            if ($exito) {
                $this->responderJson(['success' => true]);
            } else {
                $this->responderJson(['success' => false, 'error' => 'Error al guardar en base de datos.']);
            }
            return;
        }
    
        $this->responderJson(['success' => false, 'error' => 'Método inválido.']);
    }

    public function storeAlbaran() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente_albaranar = $_POST['cliente_albaranar'] ?? '';
            $fecha_albaranar = $_POST['fecha_albaranar'] ?? '';
            $codigo_articulo_albaranar = $_POST['codigo_articulo_albaranar'] ?? '';
            $cantidad_albaranar = $_POST['cantidad_albaranar'] ?? '';
            $precio_albaranar = $_POST['precio_albaranar'] ?? '';
            $descripcion_amplia_albaranar = $_POST['descripcion_amplia_albaranar'] ?? '';

            // Validaciones
            if (empty($cliente_albaranar) || empty($fecha_albaranar) || empty($codigo_articulo_albaranar) || empty($cantidad_albaranar) || empty($precio_albaranar) || empty($descripcion_amplia_albaranar)) {
                // Enviar error como respuesta JSON
                echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
                exit;  // Detener la ejecución
            }

            $ticket = new Ticket();
            $exito = $ticket->createAlbaran([
                'cliente_albaranar' => $cliente_albaranar,
                'fecha_albaranar' => $fecha_albaranar,
                'codigo_articulo_albaranar' => $codigo_articulo_albaranar,
                'cantidad_albaranar' => $cantidad_albaranar,
                'precio_albaranar' => $precio_albaranar,
                'descripcion_amplia_albaranar' => $descripcion_amplia_albaranar
            ]);


            if ($exito) {
                $this->responderJson(['success' => true]);
            } else {
                $this->responderJson(['success' => false, 'error' => 'Error al guardar en base de datos.']);
            }
            return;
        }

        $this->responderJson(['success' => false, 'error' => 'Método inválido.']);
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
