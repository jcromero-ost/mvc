<?php
require_once(__DIR__ . '/../models/Departamento.php');

$departamento = new Departamento();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $id = $_POST['id'] ?? null;
    $nombre = $_POST['nombre'] ?? null;

    if ($_POST['accion'] === 'crear' && $nombre) {
        $departamento->create($nombre);
    } elseif ($_POST['accion'] === 'editar' && $id && $nombre) {
        $departamento->update($id, $nombre);
    } elseif ($_POST['accion'] === 'eliminar' && $id) {
        $departamento->delete($id);
    }

    header('Location: ../views/departamentos.php');
    exit;
}