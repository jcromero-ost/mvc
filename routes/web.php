<?php
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/SessionController.php';
require_once __DIR__ . '/../controllers/DepartamentoController.php';
require_once __DIR__ . '/../controllers/RegistroHorarioController.php';
require_once __DIR__ . '/../controllers/ResumenHorasController.php';
require_once __DIR__ . '/../controllers/DashboardController.php';
require_once __DIR__ . '/../controllers/XgestApiController.php';

// Ruta raíz
$router->get('/', function () {
    session_start();
    if (isset($_SESSION['user'])) {
        header('Location: /tickets_pendientes');
    } else {
        header('Location: /login');
    }
    exit();
});

// Login especial para tablet o kiosko
$router->get('/login_tablet', 'UsuarioController@loginTablet');
$router->post('/login_tablet/pin', 'AuthController@loginTabletPin');

// Login / Logout
$router->get('/login', function () {
    require __DIR__ . '/../views/login.php';
});
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'SessionController@logout');

// Perfil de usuario
$router->get('/perfil_user', 'PerfilUserController@index');

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

// Vacaciones
$router->get('/vacaciones', 'VacacionesController@index');
$router->post('/store_vacaciones', 'VacacionesController@store');
$router->post('/vacaciones/estado', 'VacacionesController@getSolicitudesPorEstado');
$router->post('/vacaciones_update_estado', 'VacacionesController@updateEstado');

// Contratos Laborales
$router->get('/contratos_laborales', 'ContratoLaboralController@index');
$router->post('/contratos_laborales/guardar', 'ContratoLaboralController@guardar');

// Clientes
$router->get('/clientes', 'ClienteController@index');
$router->get('/crear_cliente', 'ClienteController@create');
$router->post('/store_cliente', 'ClienteController@store');
$router->post('/actualizar_cliente', 'ClienteController@update');
$router->post('/buscar_clientes', 'ClienteController@buscarClientes');
$router->post('/obtener_tickets_por_cliente', 'ClienteController@historial');

// Tickets
$router->get('/crear_ticket', 'TicketController@create');
$router->get('/tickets_pendientes', 'TicketController@ticketsPendientes');
$router->get('/tickets', 'TicketController@index');
$router->get('/editar_ticket', 'TicketController@edit');
$router->post('/asignar_ticket', 'TicketController@storeAsignacionesNotificaciones');
$router->post('/store_ticket', 'TicketController@store');
$router->post('/api/tickets/update', 'TicketController@storeEdit');
$router->post('/api/tickets/cambiar_estado', 'TicketController@updateEstado');

// Comentarios del Ticket
$router->post('/get_comentarios', 'TicketController@getComentarios');
$router->post('/update_comentarios', 'TicketController@updateComentario');
$router->post('/delete_comentarios', 'TicketController@deleteComentario');

// Cronómetro y Comentario Rápido
$router->post('/store_ticket_comentariosSoloFecha', 'TicketController@storeComentarioSoloFecha');
$router->post('/update_ticket_comentariosSoloFecha', 'TicketController@storeUpdateComentarioSoloFecha');

// Otras funciones del cronómetro
$router->post('/store_ticket_cronometro', 'TicketController@storeCronometro');
$router->post('/get_cronometro', 'TicketController@obtenerCronometroPorTicket');

// Otras utilidades tickets
$router->post('/update_tecnico', 'TicketController@updateTecnico');
$router->post('/tickets/tecnico', 'TicketController@ObtenerTicketsPorUsuario');
$router->post('/obtener_tickets_por_cliente', 'TicketController@obtenerTicketsPorCliente');

// Resumen Horas
$router->get('/resumen-horas', 'ResumenHorasController@index');
$router->get('/resumen-horas/usuario', 'ResumenHorasController@resumenPorUsuario');

// Medios de comunicación
$router->get('/api/medios', 'MediosComunicacionApiController@index');
$router->get('/api/medios/show', 'MediosComunicacionApiController@show');
$router->post('/api/medios/create', 'MediosComunicacionApiController@store');
$router->post('/api/medios/update', 'MediosComunicacionApiController@update');
$router->post('/api/medios/delete', 'MediosComunicacionApiController@delete');

// Dashboard
$router->get('/dashboard', 'DashboardController@index');

// Xgest API
$router->get('/xgest/clientes', 'XgestApiController@obtenerClientes');
$router->get('/xgest/articulos', 'XgestApiController@obtenerArticulos');
$router->get('/xgest/articulo', function () {
    $codigo =  $_GET['codigo'] ?? null;
    if ($codigo) {
        XgestApiController::getArticulo($codigo);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Código de artículo no proporcionado']);
    }
});
$router->get('/xgest/lineas-app-ticket', 'XgestApiController::getLineasAppTicket');
$router->get('/xgest/pedidos-con-lineas', 'XgestApiController@obtenerTodosLosPedidos');
$router->post('/xgest/crear-pedido', 'XgestApiController@crearPedidoAvanzado');
$router->get('/xgest/buscar-clientes', 'XgestApiController@buscarClientes');
$router->get('/xgest/pedidos-test', 'XgestApiController@index');
