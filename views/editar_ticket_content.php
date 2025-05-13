<div class="container-fluid mt-2">
    <div id="mensaje" class="text-success mt-2 d-none alert" role="alert"></div>
    <div>
        <div class="d-flex justify-content-between">
            <h2 class="mb-1">Editar ticket ID: <?= htmlspecialchars($ticket['id']) ?></h2>
            <?php
                require_once(__DIR__ . '/../models/Database.php');

                $db = Database::connect();

                function obtenerNombreCreador($usuario_id, $db) {
                $stmt = $db->prepare("SELECT nombre FROM usuarios WHERE id = ?");
                $stmt->execute([$usuario_id]);
                return $stmt->fetchColumn();
                }
            ?>
            <p>Ticket creado por: <?= htmlspecialchars(obtenerNombreCreador($ticket['usuario_creador_id'], $db)) ?></p>
        </div>
            <?php
                function obtenerEstadoBadge($estado) {
                switch ($estado) {
                    case 'pendiente':
                        $badgeClass = 'danger'; // Rojo
                        $texto = 'Pendiente';
                        break;
                    case 'en_revision':
                        $badgeClass = 'warning'; // Amarillo
                        $texto = 'En Revisión';
                        break;
                    case 'finalizado':
                        $badgeClass = 'success'; // Verde
                        $texto = 'Finalizado';
                        break;
                    default:
                        $badgeClass = 'secondary'; // Gris
                        $texto = 'Desconocido';
                        break;
                    }
                    return ['badgeClass' => $badgeClass, 'texto' => $texto];
                }
                // Obtener el estado y los valores correspondientes
                $estado = $ticket['estado'];
                $estadoInfo = obtenerEstadoBadge($estado);
            ?>
        <span id="estadoBadge" class="badge bg-<?= $estadoInfo['badgeClass'] ?> mb-4">
            <?= $estadoInfo['texto'] ?>
        </span>
    </div>
  <?php include_once __DIR__ . '/components/alerts.php'; ?>
  <form id="formEditarTicket" method="POST" action="/store_ticket_editar" enctype="multipart/form-data">
    <div class="row mb-3">
      <div class="col-md-6">
        <label for="editar_cliente" class="form-label">Cliente</label>
        <?php
            require_once(__DIR__ . '/../models/DatabaseXGEST.php');

            $db = DatabaseXGEST::connect();

            function obtenerNombreCliente($cliente_id, $db) {
            $stmt = $db->prepare("SELECT CNOM FROM fccli001 WHERE CCODCL = ?");
            $stmt->execute([$cliente_id]);
            return $stmt->fetchColumn();
            }
        ?>
        <div class="d-flex align-items-center">
          <input type="text" class="form-control me-2" id="cliente" name="cliente" value="<?= htmlspecialchars(obtenerNombreCliente($ticket['cliente_id'], $db)) ?>" required readonly>
          <button class="btn btn-outline-secondary btn-lupa" type="button" id="botonBuscarCliente">
            <i class="bi bi-search"></i>
          </button>
        </div>
      </div>
      <div class="col-md-6">
        <label for="medio_comunicacion" class="form-label">Medio de comunicación</label>
        <select id="medio_comunicacion" name="medio_comunicacion" class="form-select" required>
            <option value="">Seleccione un medio</option>
            <?php foreach ($medios_comunicacion as $medio): ?>
                <option value="<?= $medio['id'] ?>" <?= ($ticket['medio_id'] == $medio['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($medio['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="tecnico" class="form-label">Técnico</label>
        <select id="tecnico" name="tecnico" class="form-select" required disabled>
            <option value="">Seleccione un técnico</option>
            <?php foreach ($tecnicos as $tec): ?>
                <option value="<?= $tec['id'] ?>" <?= ($ticket['tecnico_id'] == $tec['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($tec['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-6">
        <label for="fecha_inicio" class="form-label">Fecha Inicio Ticket</label>
        <input type="datetime-local" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($ticket['fecha_inicio']) ?>" readonly>      
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-12">
        <label for="descripcion" class="form-label">Descripción</label>
        <div class="input-group">
          <textarea class="form-control" id="descripcion" name="descripcion" rows="10" required><?= htmlspecialchars($ticket['descripcion']) ?></textarea>
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-12">
        <label for="comentarios" class="form-label d-block">Comentarios</label>
        <div id="comentario-mensaje" class="text-success mt-2 d-none alert" role="alert"></div>
        <button id="nuevoComentarioBtn" type="button" name="accion" value="crear" class="btn btn-primary">Nuevo comentario</button>
        <button id="nuevoComentarioInternoBtn" type="button" name="accion" value="crear" class="btn btn-secondary">Nuevo comentario Interno</button>
        <div id="comentariosDiv" class="mt-2"></div>
      </div>
    </div>
    <script>
      const comentarios = <?php echo json_encode($comentarios); ?>;
    </script>
<hr>
    <input type="hidden" id="id" name="id" value="<?= htmlspecialchars($ticket['id']) ?>"> <!-- ID del ticket -->
    <input type="hidden" id="estado" name="id" value="<?= htmlspecialchars($ticket['estado']) ?>"> <!-- estado del ticket -->

    <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">Aplicar cambios</button>
        <div class="d-flex">
            <button type="button" id="botonFinalizarTicket" name="botonFinalizarTicket" class="btn btn-success">Finalizar ticket</button>
            <button type="button" id="botonPendienteTicket" name="botonPendienteTicket" class="btn btn-danger d-none">Marcar como pendiente</button>
        </div>
    </div>
  </form>
</div>

<!-- Modal Cliente -->
<?php include_once __DIR__ . '/components/modal_cliente_ticket.php'; ?>

<?php include_once __DIR__ . '/components/modal_tickets/modal_tiempo_ticket.php'; ?>

<?php include_once __DIR__ . '/components/modal_tickets/modal_traspasar_ticket.php'; ?>




<script src="/public/js/tickets/tickets_editar.js" defer></script>
<script src="/public/js/tickets/tickets_comentarios.js" defer></script>
<script src="/public/js/tickets/tickets_cronometro.js" defer></script>

