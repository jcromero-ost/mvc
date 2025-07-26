<?php
class ContratoCliController
{
    public function index()
    {
        require_once __DIR__ . '/../models/ContratoCli.php';
        $model = new ContratoCli();
        $contratos = $model->getAllWithServicios();

        require_once __DIR__ . '/../views/contratos/contratos_clientes.php';
    }

    public static function create()
    {
        $view = 'contratos/crear_contratoCli_content.php';
        require __DIR__ . '/../views/layout.php';
    }

    public static function store()
    {
        require_once(__DIR__ . '/../models/ContratoCli.php');

        $cliente_id = $_POST['cliente_id'] ?? null;
        $fecha_alta = $_POST['fecha_alta'] ?? null;
        $servicios = $_POST['servicio'] ?? [];
        $usuario_id = $_SESSION['user_id'] ?? null;

        if (!$cliente_id || !$fecha_alta || empty($servicios)) {
            die('Datos incompletos');
        }

        $contratoModel = new ContratoCli();
        $resultado = $contratoModel->create($cliente_id, $fecha_alta, $usuario_id, $servicios );

        if ($resultado) {
            header("Location: /contratos-clientes");
            exit;
        } else {
            die('Error al guardar el contrato.');
        }
    }
    
}

