<?php
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/SessionController.php';
require_once __DIR__ . '/../controllers/DepartamentoController.php';

// Ruta raíz
$router->get('/', function () {
    session_start();
    if (isset($_SESSION['user'])) {
        header('Location: /usuarios');
    } else {
        header('Location: /login');
    }
    exit();
});

// Login / Logout
$router->get('/login', function () {
    require __DIR__ . '/../views/login.php';
});
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'SessionController@logout');

// Usuarios
$router->get('/usuarios', 'UsuarioController@index');
$router->get('/crear_usuario', 'UsuarioController@create');
$router->post('/store_usuario', 'UsuarioController@store');

// Departamentos
$router->post('/crear_departamento', 'DepartamentoController@store');
$router->post('/editar_departamento', 'DepartamentoController@update');
$router->post('/eliminar_departamento', 'DepartamentoController@delete');

// Dashboard
require_once __DIR__ . '/../controllers/DashboardController.php';

$router->get('/dashboard', 'DashboardController@index');
