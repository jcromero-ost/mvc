<?php
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/SessionController.php';
require_once __DIR__ . '/../controllers/DepartamentoController.php';
require_once __DIR__ . '/../controllers/RegistroHorarioController.php';

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
$router->get('/departamentos', 'DepartamentoController@index');
$router->post('/crear_departamento', 'DepartamentoController@store');
$router->post('/editar_departamento', 'DepartamentoController@update');
$router->post('/eliminar_departamento', 'DepartamentoController@delete');

// Registro de Horario
$router->get('/registro_horario', 'RegistroHorarioController@index');
$router->post('/registro_horario/store', 'RegistroHorarioController@store');
$router->get('/registro_horario/historial', 'RegistroHorarioController@historial');
$router->get('/registro-horario/listado', 'RegistroHorarioController@listado');
$router->post('/registro-horario/buscar', 'RegistroHorarioController@buscar');
$router->get('/usuarios/activos', 'UsuarioController@usuariosActivos');
$router->post('/registro-horario/buscar-jornadas', 'RegistroHorarioController@buscarJornadas');
$router->get('/registro-horario/imprimir', 'RegistroHorarioController@imprimir');

// Calendario Laboral
$router->get('/calendario_laboral', 'CalendarioLaboralController@index');

// Clientes
$router->get('/crear_cliente', 'ClienteController@create');
$router->get('/clientes', 'ClienteController@index');
$router->post('/store_cliente', 'ClienteController@store');

// Tickets
$router->get('/crear_ticket', 'TicketController@create');
$router->get('/tickets_pendientes', 'TicketController@ticketsPendientes');
$router->get('/tickets', 'TicketController@index');






// Dashboard
require_once __DIR__ . '/../controllers/DashboardController.php';

$router->get('/dashboard', 'DashboardController@index');
