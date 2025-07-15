<?php
    if (session_status() === PHP_SESSION_NONE) 
        session_start(); 

    require_once(__DIR__ . '/../models/MediosComunicacion.php');
    require_once(__DIR__ . '/../models/Usuario.php');
    require_once(__DIR__ . '/../models/Cliente.php');
    require_once(__DIR__ . '/../models/Ticket.php');
    require_once(__DIR__ . '/../models/TicketAsignacion.php');
    require_once(__DIR__ . '/../models/DatabaseXGEST.php');

    $mediosModel = new MedioComunicacion();
    $tecnicosModel = new Usuario();
    $clienteModel = new Cliente();
    $ticketModel = new Ticket();

    $medios_comunicacion = $mediosModel->getAll();
    $tecnicos = $tecnicosModel->getAllUsuarios();
    $clientes = $clienteModel->getAllClientes();

    $AsignacionesModel = new TicketAsignacion();
    $asignaciones = $AsignacionesModel->getAllAsignaciones();

    $asignacionesPorTicket = [];

    foreach ($asignaciones as $asignacion) {
        $ticketId = $asignacion['ticket_id'];
        if (!isset($asignacionesPorTicket[$ticketId])) {
            $asignacionesPorTicket[$ticketId] = [];
        }
        $asignacionesPorTicket[$ticketId][] = $asignacion;
    }

    $ticket_id = $_GET['id'] ?? null;

    if (!$ticket_id) {
        header("Location: /tickets_pendientes");
        exit;
    }

    $ticket = $ticketModel->getById($ticket_id);
    $comentarios = $ticketModel->getAllComentarios($ticket_id);
    $tiempo_total = $ticketModel->getTiempoTotal($ticket_id);
    $segundos = (int) $tiempo_total['tiempo_total'];
    $horas = floor($segundos / 3600);
    $minutos = floor(($segundos % 3600) / 60);
    $restoSegundos = $segundos % 60;
    $tiempoFormateado = sprintf('%02d:%02d:%02d', $horas, $minutos, $restoSegundos);

    $dbXGEST = DatabaseXGEST::connect();
    $stmt = $dbXGEST->prepare("SELECT * FROM fccli001 WHERE CCODCL = ?");
    $stmt->execute([$ticket['cliente_id']]);
    $datosCliente = $stmt->fetch(PDO::FETCH_ASSOC);

    $view = './editar_ticket_content.php';
    include __DIR__ . '/layout.php';
