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
    
                if (Usuario::update($datos)) {
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

    public function cambiar_passwd() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'cambiar_passwd') {
            $id = $_POST['id'] ?? null;
            $password = $_POST['password'] ?? null;
        
            if ($id && $password) {
                $datos = [
                    'id' => $id,
                    'password' => $password
                ];
        
                if (Usuario::cambiar_passwd($datos)) {
                    $_SESSION['success'] = "Se ha enviado un correo electrónico al usuario";
                } else {
                    $_SESSION['error'] = "Error al cambiar la contraseña";
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

            // Procesar imagen recortada en base64
            if (!empty($_POST['foto_recortada'])) {
                $foto = $_POST['foto_recortada']; // Base64 completa
            } else {
                $foto = 'default.jpeg'; // O podrías guardar null
            }
            


            $usuario = new Usuario();
            $usuario->create([
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

    public function usuariosActivos()
{
    header('Content-Type: application/json');

    $usuario = new Usuario();
    $usuarios = $usuario->obtenerUsuariosActivos();

    echo json_encode(['success' => true, 'data' => $usuarios]);
}


}
if (isset($_POST['accion'])) {
    $controller = new UsuarioController();
    
    switch ($_POST['accion']) {
        case 'editar':
            $controller->update();
            break;
        case 'eliminar':
            $controller->delete();
            break;
        case 'cambiar_passwd':
            $controller->cambiar_passwd();
            break;
    }
}
