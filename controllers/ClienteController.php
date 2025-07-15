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
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

            $exito = Cliente::update($data);

            $this->responderJson($exito ? ['success' => true] : ['success' => false, 'error' => 'Error al guardar en base de datos.']);
            return;
        }

        $this->responderJson(['success' => false, 'error' => 'Método inválido.']);
    }

    public function delete_cliente() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'eliminar') {
            $id = $_POST['id'] ?? null;
            if ($id) Cliente::delete($id);
            else echo "ID no válido para eliminar.";
        }
    }

    public function store() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $_POST['id'] ?? '',
                'nombre' => $_POST['nombre'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'dni' => $_POST['dni'] ?? '',
                'email' => $_POST['email'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'ciudad' => $_POST['ciudad'] ?? '',
                'cp' => $_POST['cp'] ?? '',
                'provincia' => $_POST['provincia'] ?? ''
            ];

            if (empty($data['nombre']) || empty($data['telefono']) || empty($data['dni']) || empty($data['email'])) {
                $_SESSION['error'] = 'Todos los campos obligatorios deben completarse.';
                header('Location: /crear_cliente');
                exit;
            }

            Cliente::create($data);

            $_SESSION['success'] = 'Cliente creado correctamente.';
            header('Location: /clientes');
            exit;
        }

        header('Location: /clientes');
        exit;
    }

    public function storeTickets() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $_POST['id'] ?? '',
                'nombre' => $_POST['nombre'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'dni' => $_POST['dni'] ?? '',
                'email' => $_POST['email'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'ciudad' => $_POST['ciudad'] ?? '',
                'cp' => $_POST['cp'] ?? '',
                'provincia' => $_POST['provincia'] ?? ''
            ];

            if (empty($data['nombre']) || empty($data['telefono']) || empty($data['dni']) || empty($data['email'])) {
                $this->responderJson(['success' => false, 'error' => 'Faltan campos obligatorios']);
            }

            $exito = Cliente::create($data);

            $this->responderJson($exito ? ['success' => true] : ['success' => false, 'error' => 'Error al guardar en base de datos.']);
            return;
        }

        $this->responderJson(['success' => false, 'error' => 'Método inválido.']);
    }

    private function responderJson($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

if (isset($_POST['accion'])) {
    $controller = new ClienteController();

    switch ($_POST['accion']) {
        case 'editar':
            $controller->update_cliente();
            break;
        case 'eliminar':
            $controller->delete_cliente();
            break;
    }
}
