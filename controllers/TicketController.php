<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Ticket.php';
require_once __DIR__ . '/../models/MediosComunicacion.php';

class TicketController {

    public function create() {
        $mediosModel = new MedioComunicacion();
        $medios_comunicacion = $mediosModel->getAll();
        require_once __DIR__ . '/../views/crear_ticket.php';
    }

    public function ticketsPendientes() {
        $tickets = Usuario::all();
        require_once __DIR__ . '/../views/tickets_pendientes.php';
    }

    public function index() {
        $tickets = Usuario::all();
        require_once __DIR__ . '/../views/tickets.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'editar') {
            $id = $_POST['id'] ?? null;
            $nombre = $_POST['nombre'] ?? null;
            $alias = $_POST['alias'] ?? null;
            $email = $_POST['email'] ?? null;
            $telefono = $_POST['telefono'] ?? null;
            $fecha_ingreso = $_POST['fecha_ingreso'] ?? null;
            $departamento_id = $_POST['departamento_id'] ?? null;
            $activo = $_POST['activo'] ?? 1;
    
            // Procesar imagen recortada en base64 (si existe)
            if (!empty($_POST['foto_recortada']) && strpos($_POST['foto_recortada'], 'data:image') === 0) {
                $foto = $_POST['foto_recortada']; // Base64 válida
                $datos['foto'] = $foto;
            }
            
    
            if ($id && $nombre && $email) {
                $datos = [
                    'id' => $id,
                    'nombre' => $nombre,
                    'alias' => $alias,
                    'email' => $email,
                    'telefono' => $telefono,
                    'fecha_ingreso' => $fecha_ingreso,
                    'departamento_id' => $departamento_id,
                    'activo' => $activo
                ];
    
                // Solo incluir 'foto' si se subió una nueva
                if (!empty($foto)) {
                    $datos['foto'] = $foto;
                }
    
                if (Ticket::update($datos)) {
                    $_SESSION['success'] = "Usuario actualizado correctamente.";
                } else {
                    $_SESSION['error'] = "Error al actualizar el usuario.";
                }
    
                header("Location: ../views/usuarios.php");
                exit;
            } else {
                $_SESSION['error'] = "Faltan datos obligatorios.";
                header("Location: ../views/usuarios.php");
                exit;
            }
        }
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
            $cliente = $_POST['cliente'] ?? '';
            $tecnico = $_POST['tecnico'] ?? '';
            $fecha_inicio = $_POST['fecha_inicio'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';

            // Validaciones mínimas
            if (empty($medio_comunicacion) || empty($cliente) || empty($tecnico) || empty($fecha_inicio) || empty($descripcion)) {
                $_SESSION['error'] = 'Todos los campos son obligatorios.';
                header('Location: /crear_ticket');
                exit;
            }

            $ticket = new Ticket();
            $ticket->create([
                'medio_comunicacion' => $medio_comunicacion,
                'cliente' => $cliente,
                'tecnico' => $tecnico,
                'fecha_inicio' => $fecha_inicio,
                'descripcion' => $descripcion
            ]);


            $_SESSION['success'] = 'Ticket creado correctamente.';
            header('Location: /tickets_pendientes');
            exit;
        }

        // Si entra por GET o sin POST válido, redirigir
        header('Location: /tickets_pendientes');
        exit;
    }

}
if (isset($_POST['accion'])) {
    $controller = new TicketController();
    
    switch ($_POST['accion']) {
        case 'editar':
            $controller->update();
            break;
        case 'eliminar':
            $controller->delete();
            break;
    }
}
