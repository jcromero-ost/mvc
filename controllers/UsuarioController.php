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

    public function store() {
        echo "store() fue llamado<br>";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? null;
            $alias = $_POST['alias'] ?? null;
            $email = $_POST['email'] ?? null;
            $telefono = $_POST['telefono'] ?? null;
            $fecha_ingreso = $_POST['fecha_ingreso'] ?? null;
            $departamento_id = $_POST['departamento_id'] ?? null;
            $password = $_POST['password'] ?? null;
            $confirm_password = $_POST['confirm_password'] ?? null;
            $activo = $_POST['activo'] ?? 1;
    
            if ($password !== $confirm_password) {
                die("Las contraseñas no coinciden.");
            }
    
            $foto = 'default.jpeg'; // valor por defecto

            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $permitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

                if (in_array($ext, $permitidos)) {
                    $fotoNombre = uniqid('user_') . '.' . $ext;
                    move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . '/../public/images/' . $fotoNombre);
                    $foto = $fotoNombre; // solo si fue subida correctamente
                } else {
                    die("Formato de imagen no permitido.");
                }
            }

    
            if ($nombre && $email && $password && $departamento_id) {
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
                header("Location: ../views/usuarios.php");
                exit;
            } else {
                echo "Faltan datos obligatorios.";
            }
        }
    }
    
    
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new UsuarioController();
    $controller->store();
}