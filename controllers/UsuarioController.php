<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Departamento.php';

class UsuarioController {

    public function index() {
        $usuarios = Usuario::all();
        require_once __DIR__ . '/../views/usuarios.php';
    }

    public function create() {
        $departamentoModel = new Departamento();
        $departamentos = $departamentoModel->getAll();
        require_once __DIR__ . '/../views/crear_usuario.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
            $id = $_POST['id'] ?? null;
            $nombre = $_POST['nombre'] ?? null;
            $alias = $_POST['alias'] ?? null;
            $email = $_POST['email'] ?? null;
            $telefono = $_POST['telefono'] ?? null;
            $fecha_ingreso = $_POST['fecha_ingreso'] ?? null;
            $activo = $_POST['activo'] ?? 1;
    
            if ($id && $nombre && $email) {
                Usuario::update([
                    'id' => $id,
                    'nombre' => $nombre,
                    'alias' => $alias,
                    'email' => $email,
                    'telefono' => $telefono,
                    'fecha_ingreso' => $fecha_ingreso,
                    'activo' => $activo
                ]);
                $_SESSION['mensaje'] = "Usuario actualizado correctamente.";
                    header("Location: ../views/usuarios.php");
                exit;

            } else {
                echo "Faltan datos obligatorios para la edición.";
            }
        }
    }
    
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                Usuario::delete($id);
                $_SESSION['mensaje'] = "Usuario eliminado correctamente.";
                header("Location: ../views/usuarios.php");
                exit;

            } else {
                
                echo "ID no válido para eliminar.";
            }
        }
    }
    

    public function store() {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $alias = $_POST['alias'] ?? '';
            $email = $_POST['email'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';
            $departamento_id = $_POST['departamento_id'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $activo = $_POST['activo'] ?? 1;
            $foto = 'default.jpeg';

            // Validaciones
            if ($password !== $confirm_password) {
                $_SESSION['error'] = 'Las contraseñas no coinciden.';
                header('Location: /crear_usuario');
                exit;
            }

            if (empty($nombre) || empty($email) || empty($password) || empty($departamento_id)) {
                $_SESSION['error'] = 'Todos los campos obligatorios deben completarse.';
                header('Location: /crear_usuario');
                exit;
            }

            // Subida de foto
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $permitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

                if (in_array($ext, $permitidos)) {
                    $fotoNombre = uniqid('user_') . '.' . $ext;
                    move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . '/../public/images/' . $fotoNombre);
                    $foto = $fotoNombre;
                } else {
                    $_SESSION['error'] = 'Formato de imagen no permitido.';
                    header('Location: /crear_usuario');
                    exit;
                }
            }

            // Crear usuario
            Usuario::create([
                'nombre' => $nombre,
                'alias' => $alias,
                'email' => $email,
                'telefono' => $telefono,
                'fecha_ingreso' => $fecha_ingreso,
                'password' => $password,
                'departamento_id' => $departamento_id,
                'activo' => $activo,
                'foto' => $foto
            ]);

            $_SESSION['success'] = 'Usuario creado correctamente.';
            header('Location: /usuarios');
            exit;
        }

        // Si entra por GET o sin POST válido, redirigir
        header('Location: /usuarios');
        exit;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new UsuarioController();
    
    switch ($_POST['accion']) {
        case 'editar':
            $controller->update();
            break;
        case 'eliminar':
            $controller->delete();
            break;
    }
}

