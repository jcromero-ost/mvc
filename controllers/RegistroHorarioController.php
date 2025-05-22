<?php
require_once __DIR__ . '/../models/RegistroHorario.php';

class RegistroHorarioController
{
    public function index()
    {
        $view = 'registro_horario_content.php';
        require __DIR__ . '/../views/layout.php';
    }

    public function store()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
            return;
        }

        $userId = $_POST['user_id'] ?? null;
        $tipoEvento = $_POST['tipo_evento'] ?? null;

        if (empty($userId) || empty($tipoEvento)) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
            return;
        }

        $registro = new RegistroHorario();
        if ($registro->registrarEvento($userId, $tipoEvento)) {
            echo json_encode(['success' => true, 'message' => 'Evento registrado.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar evento.']);
        }
    }

    public function historial()
{
    header('Content-Type: application/json');
    
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'message' => 'No autorizado.']);
        return;
    }

    $registro = new RegistroHorario();
    $estado = $registro->obtenerEstadoActual($_SESSION['user']['id']);

    echo json_encode(['success' => true, 'data' => $estado]);
}

public function listado()
{
    $view = 'reg_hor_list_content.php';
    require __DIR__ . '/../views/layout.php';
}

public function buscar()
{
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
        return;
    }

    $usuario = $_POST['usuario'] ?? null;
    $fechaDesde = $_POST['fecha_desde'] ?? null;
    $fechaHasta = $_POST['fecha_hasta'] ?? null;
    $tipoEvento = $_POST['tipo_evento'] ?? null;
    $pagina = max((int)($_POST['page'] ?? 1), 1);
    $limite = max((int)($_POST['limit'] ?? 10), 1);



    $registro = new RegistroHorario();
    $resultados = $registro->buscarRegistros($usuario, $fechaDesde, $fechaHasta, $tipoEvento, $pagina, $limite);

    echo json_encode(['success' => true, 'data' => $resultados]);
}

public function buscarJornadas()
{
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
        return;
    }

    $usuario = $_POST['usuario'] ?? null;
    $fechaDesde = $_POST['fecha_desde'] ?? null;
    $fechaHasta = $_POST['fecha_hasta'] ?? null;
    $pagina = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $limite = isset($_POST['limit']) ? (int)$_POST['limit'] : 10;

    $registro = new RegistroHorario();
    $resultado = $registro->obtenerJornadasFiltradas($usuario, $fechaDesde, $fechaHasta, $pagina, $limite);

    echo json_encode([
        'success' => true,
        'data' => [
            'jornadas' => $resultado['jornadas'],
            'total' => $resultado['total']
        ],
        'limit' => $limite,
        'page' => $pagina
    ]);
    
}

public function imprimir()
{
    $usuario = $_GET['usuario'] ?? null;
    $fechaDesde = $_GET['desde'] ?? null;
    $fechaHasta = $_GET['hasta'] ?? null;

    $registro = new RegistroHorario();
    $resultado = $registro->obtenerJornadasFiltradas($usuario, $fechaDesde, $fechaHasta, 1, 9999); // traer todos

    $jornadas = $resultado['jornadas'];

    

    require __DIR__ . '/../views/reg_hor_list_print.php';
}

    public function update_registro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data['hora_inicio'], $data['hora_fin'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
                exit;
            }

            $resultado = RegistroHorario::update([
                'id_inicio' => $data['id_inicio'],
                'id_fin' => $data['id_fin'],
                'motivo_inicio' => $data['motivo_inicio'],
                'motivo_fin' => $data['motivo_fin'],
                'hora_inicio' => $data['hora_inicio'],
                'hora_fin' => $data['hora_fin']
            ]);

            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Actualización exitosa']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error al actualizar']);
            }
        }
    }

}
