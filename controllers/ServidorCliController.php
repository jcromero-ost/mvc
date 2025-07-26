<?php
require_once 'models/ServidorCli.php';

class ServidorCliController {
    public function index() {
        $contrato_id = $_GET['contrato_id'] ?? null;

        if (!$contrato_id) {
            echo "ID de contrato no proporcionado.";
            return;
        }

        $servidores = ServidorCli::getByContratoId($contrato_id);

        require 'views/contratos/servidores_cli.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'contrato_id' => $_POST['contrato_id'],
                'nombre'      => $_POST['nombre'],
                'ip'          => $_POST['ip'],
                'descripcion' => $_POST['descripcion']
            ];
            ServidorCli::create($data);
            header("Location: /servidores_cli?contrato_id=" . $data['contrato_id']);
            exit;
        }
    }
}
