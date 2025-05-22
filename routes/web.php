<?php
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/SessionController.php';
require_once __DIR__ . '/../controllers/DepartamentoController.php';
require_once __DIR__ . '/../controllers/RegistroHorarioController.php';
require_once __DIR__ . '/../controllers/ResumenHorasController.php';


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
$router->post('/registros_horarios/update', 'RegistroHorarioController@update_registro');


// Calendario Laboral
$router->get('/calendario_laboral', 'CalendarioLaboralController@index');

// Festivos
$router->get('/festivos', 'FestivoController@index');
$router->post('/festivos/store', 'FestivoController@store');
$router->post('/festivos/delete', 'FestivoController@delete');

//Resumen Horario
$router->get('/resumen-horas', 'ResumenHorasController@index');
$router->get('/resumen-horas/usuario', 'ResumenHorasController@resumenPorUsuario');




// Clientes
$router->get('/crear_cliente', 'ClienteController@create');
$router->get('/clientes', 'ClienteController@index');
$router->get('/delete_cliente', 'ClienteController@delete_cliente');
$router->get('/clientes_historial', 'ClienteController@clientes_historial');
$router->post('/store_cliente', 'ClienteController@store');
$router->post('/store_cliente_ticket', 'ClienteController@storeTickets');

// Tickets
$router->get('/crear_ticket', 'TicketController@create');
$router->get('/tickets_pendientes', 'TicketController@ticketsPendientes');
$router->get('/tickets', 'TicketController@index');
$router->get('/editar_ticket', 'TicketController@edit');
$router->post('/store_ticket', 'TicketController@store');
$router->post('/store_ticket_editar', 'TicketController@storeEdit');
$router->post('/store_ticket_comentarios', 'TicketController@storeComentarios');
$router->post('/delete_comentarios', 'TicketController@deleteComentarios');
$router->post('/update_comentarios', 'TicketController@updateComentarios');
$router->post('/update_estado', 'TicketController@updateEstado');
$router->post('/get_comentarios', 'TicketController@obtenerComentariosPorTicket');


$router->post('/obtener_tickets_por_cliente', 'TicketController@obtenerTicketsPorCliente');

$router->post('/store_ticket_cronometro', 'TicketController@storeCronometro');
$router->post('/get_cronometro', 'TicketController@obtenerCronometroPorTicket');

$router->post('/update_tecnico', 'TicketController@updateTecnico');

$router->post('/store_albaranar', 'TicketController@storeAlbaran');






// Contratos Laborales
$router->get('/contratos_laborales', 'ContratoLaboralController@index');
$router->post('/contratos_laborales/guardar', 'ContratoLaboralController@guardar');




// Dashboard
require_once __DIR__ . '/../controllers/DashboardController.php';

$router->get('/dashboard', 'DashboardController@index');
