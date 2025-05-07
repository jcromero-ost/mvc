<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Cliente.php';

class ClienteController {

    public function create() {
        require_once __DIR__ . '/../views/crear_cliente.php';
    }

    public function index() {
        require_once __DIR__ . '/../views/clientes.php';
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
                    $_SESSION['success'] = "Cliente actualizado correctamente.";
                } else {
                    $_SESSION['error'] = "Error al actualizar el cliente.";
                }
    
                header("Location: ../views/clientes.php");
                exit;
            } else {
                $_SESSION['error'] = "Faltan datos obligatorios.";
                header("Location: ../views/clientes.php");
                exit;
            }
        }
    }
    
    
    
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                Cliente::delete($id);
                $_SESSION['mensaje'] = "Cliente eliminado correctamente.";
                header("Location: ../views/clientes.php");
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
            $id = $_POST['id'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $dni = $_POST['dni'] ?? '';
            $email = $_POST['email'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $ciudad = $_POST['ciudad'] ?? '';
            $cp = $_POST['cp'] ?? '';
            $provincia = $_POST['provincia'] ?? '';

            // Validaciones
            if (empty($nombre) || empty($telefono) || empty($dni) || empty($email)) {
                $_SESSION['error'] = 'Todos los campos obligatorios deben completarse.';
                header('Location: /crear_cliente');
                exit;
            }

            $cliente = new Cliente();
            $cliente->create([
                'id' => $id,
                'nombre' => $nombre,
                'telefono' => $telefono,
                'dni' => $dni,
                'email' => $email,
                'direccion' => $direccion,
                'ciudad' => $ciudad,
                'cp' => $cp,
                'provincia' => $provincia
            ]);


            $_SESSION['success'] = 'Cliente creado correctamente.';
            header('Location: /clientes');
            exit;
        }

        // Si entra por GET o sin POST válido, redirigir
        header('Location: /clientes');
        exit;
    }

    public function storeTickets() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $dni = $_POST['dni'] ?? '';
            $email = $_POST['email'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $ciudad = $_POST['ciudad'] ?? '';
            $cp = $_POST['cp'] ?? '';
            $provincia = $_POST['provincia'] ?? '';

            // Validaciones
            if (empty($nombre) || empty($telefono) || empty($dni) || empty($email)) {
                $_SESSION['error'] = 'Todos los campos obligatorios deben completarse.';
                header('Location: /crear_cliente');
                exit;
            }

            $cliente = new Cliente();
            $cliente->create([
                'id' => $id,
                'nombre' => $nombre,
                'telefono' => $telefono,
                'dni' => $dni,
                'email' => $email,
                'direccion' => $direccion,
                'ciudad' => $ciudad,
                'cp' => $cp,
                'provincia' => $provincia
            ]);


            $_SESSION['success'] = 'Cliente creado correctamente.';
            header('Location: /crear_ticket');
            exit;
        }

        // Si entra por GET o sin POST válido, redirigir
        header('Location: /crear_ticket');
        exit;
    }
}
if (isset($_POST['accion'])) {
    $controller = new ClienteController();
    
    switch ($_POST['accion']) {
        case 'editar':
            $controller->update();
            break;
        case 'eliminar':
            $controller->delete();
            break;
    }
}