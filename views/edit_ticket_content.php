<section class="container mt-4 d-flex">

    <div class="content-main flex-grow-1" id="content-main">

        <!-- Título principal con estado visual -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Editar Ticket ID: <?= htmlspecialchars($ticket['id']) ?></h2>

            <div class="d-flex align-items-center">
                <span class="badge bg-warning text-dark me-2">
                    <?= ucfirst($ticket['estado'] ?? 'Desconocido') ?>
                </span>
                <button type="button" class="btn btn-primary btn-sm" id="btnToggleCronometro" title="Abrir cronómetro">
                    <i class="bi bi-stopwatch"></i>
                </button>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">

                <!-- FORMULARIO SOLO LECTURA -->
                <form>
                    <!-- FILA 1: Cliente y Medio de comunicación -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Cliente</label>
                            <div class="input-group">
                                <input type="text" class="form-control bg-light" 
                                    value="<?= htmlspecialchars($cliente['CNOM'] ?? 'Cliente no encontrado') ?>" disabled>
                                <button type="button" 
                                        class="btn btn-primary btn-ver-cliente" 
                                        title="Ver información del cliente" 
                                        data-cliente-id="<?= htmlspecialchars($ticket['cliente_id']) ?>">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Medio de comunicación</label>
                            <input type="text" class="form-control bg-light"
                                value="<?= htmlspecialchars($ticket['medio_nombre'] ?? 'No especificado') ?>" disabled>
                        </div>
                    </div>

                    <!-- FILA 2: Técnico y Fecha Inicio -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Técnico</label>
                            <input type="text" class="form-control bg-light"
                                value="<?= htmlspecialchars($ticket['tecnico_nombre'] ?? 'No asignado') ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha Inicio Ticket</label>
                            <input type="text" class="form-control bg-light"
                                value="<?= !empty($ticket['fecha_inicio']) ? date('d/m/Y, H:i', strtotime($ticket['fecha_inicio'])) : '' ?>"
                                disabled>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripción</label>
                        <textarea class="form-control bg-light" rows="4" disabled><?= htmlspecialchars($ticket['descripcion'] ?? '') ?></textarea>
                    </div>
                </form>

            </div>
        </div>

        <input type="hidden" id="ticket_id" value="<?= $ticket['id'] ?? 0 ?>">
        <input type="hidden" id="usuario_actual_id" value="<?= $_SESSION['id'] ?>">


        <!-- BLOQUE COMENTARIOS -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>Comentarios</h4>
            <button type="button" class="btn btn-sm btn-dark" id="btnNuevoComentario">Nuevo comentario Interno</button>
        </div>

        <div id="listaComentarios" class="list-group mb-4">
            <!-- Comentarios cargados vía JS -->
        </div>


    </div> <!-- fin content-main -->

    <?php include __DIR__ . '/components/modal_tickets/modal_cronometro.php'; ?>   

</section>

<?php include __DIR__ . '/components/modal/modal_client_info.php'; ?>
<?php include __DIR__ . '/components/modal/modal_editor_comentario.php'; ?>

<script src="/public/js/tickets/comentarios.js"></script>
<script src="/public/js/tickets/editor_comentarios.js"></script>
<script src="/public/js/tickets/cronometro.js"></script>
