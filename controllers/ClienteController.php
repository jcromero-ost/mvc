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

    public function clientes_historial() {
        require_once __DIR__ . '/../views/clientes_historial.php';
    }

    // Actualizar datos del cliente
    public function update_cliente() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recogemos los datos del formulario
            $data = [
                'id'        => $_POST['id'] ?? '',
                'nombre'    => $_POST['nombre'] ?? '',
                'telefono'  => $_POST['telefono'] ?? '',
                'dni'       => $_POST['dni'] ?? '',
                'email'     => $_POST['email'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'ciudad'    => $_POST['ciudad'] ?? '',
                'cp'        => $_POST['cp'] ?? '',
                'provincia' => $_POST['provincia'] ?? ''
            ];

            $cliente = new Cliente();
            $exito = $cliente->update($data);

            if ($exito) {
                $this->responderJson(['success' => true]);
            } else {
                $this->responderJson(['success' => false, 'error' => 'Error al guardar en base de datos.']);
            }
            return;
        }

        $this->responderJson(['success' => false, 'error' => 'Método inválido.']);
    }
    
    public function delete_cliente() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                Cliente::delete($id);
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

    private function responderJson($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
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
                $this->responderJson(['success' => false, 'error' => 'Faltan campos obligatorios']);
            }

            $cliente = new Cliente();
            $exito = $cliente->create([
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

            if ($exito) {
                $this->responderJson(['success' => true]);
            } else {
                $this->responderJson(['success' => false, 'error' => 'Error al guardar en base de datos.']);
            }
            return;
        }
        // Si entra por GET o sin POST válido, redirigir
        $this->responderJson(['success' => false, 'error' => 'Método inválido.']);
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