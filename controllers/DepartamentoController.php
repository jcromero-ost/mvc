<?php
require_once(__DIR__ . '/../models/Departamento.php');

class DepartamentoController {

    public function store() {
        if (session_status() === PHP_SESSION_NONE) 
            session_start(); 


        $nombre = $_POST['nombre'] ?? null;

        if ($nombre) {
            $departamento = new Departamento();
            $departamento->create($nombre);
            $_SESSION['success'] = 'Departamento creado correctamente.';
        } else {
            $_SESSION['error'] = 'El nombre del departamento es obligatorio.';
        }

        header('Location: /departamentos');
        exit;
    }

    public function update() {
        session_start();

        $id = $_POST['id'] ?? null;
        $nombre = $_POST['nombre'] ?? null;

        if ($id && $nombre) {
            $departamento = new Departamento();
            $departamento->update($id, $nombre);
            $_SESSION['success'] = 'Departamento actualizado correctamente.';
        } else {
            $_SESSION['error'] = 'Faltan datos para editar.';
        }

        header('Location: /departamentos');
        exit;
    }

    public function delete() {
        session_start();

        $id = $_POST['id'] ?? null;

        if ($id) {
            $departamento = new Departamento();
            $departamento->delete($id);
            $_SESSION['success'] = 'Departamento eliminado.';
        } else {
            $_SESSION['error'] = 'ID inválido.';
        }

        header('Location: /departamentos');
        exit;
    }
}
