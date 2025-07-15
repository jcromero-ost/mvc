<?php
// controllers/XgestApiController.php
require_once __DIR__ . '/../models/Xgest.php';
require_once __DIR__ . '/../models/Database.php'; // solo si usas Database::connect()

class XgestApiController
{
    public static function obtenerClientes()
    {
        header('Content-Type: application/json');
        $clientes = Xgest::getClientes();
        echo json_encode($clientes);
    }

    public static function getArticulo($codigo)
    {
        header('Content-Type: application/json');

        if (!$codigo) {
            http_response_code(400);
            echo json_encode(['error' => 'Código de artículo no proporcionado']);
            return;
        }

        $articulo = Xgest::getArticulo($codigo);

        if ($articulo) {
            echo json_encode($articulo);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Artículo no encontrado']);
        }
    }

    public static function getLineasAppTicket()
    {
        header('Content-Type: application/json');
        $lineas = Xgest::obtenerLineasAppTicket(); // ya usa global $pdo_xgest
        echo json_encode(['success' => true, 'data' => $lineas]);
    }

    public static function obtenerArticulos()
    {
        header('Content-Type: application/json');
        $articulos = Xgest::getArticulos();
        echo json_encode($articulos);
    }

    public static function obtenerTodosLosPedidos()
{
    header('Content-Type: application/json');
    $data = Xgest::obtenerTodosLosPedidosConLineas();
    echo json_encode(['success' => true, 'data' => $data]);
}

public static function crearPedidoAvanzado()
{
    header('Content-Type: application/json');

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!$data || !isset($data['cliente_id'], $data['lineas'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Faltan datos obligatorios.']);
        return;
    }

    $res = Xgest::insertarPedidoConLineasAvanzado($data);
    echo json_encode($res);
}

public static function buscarClientes()
{
    header('Content-Type: application/json');
    $q = $_GET['q'] ?? '';

    if (!$q || strlen($q) < 2) {
        echo json_encode([]);
        return;
    }

    $clientes = Xgest::buscarClientes($q);
    echo json_encode($clientes);
}



public static function index()
    {
        require __DIR__ . '/../views/xgest/crear_pedido_test.php';
    }

}
