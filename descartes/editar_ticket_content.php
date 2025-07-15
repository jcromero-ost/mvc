<?php
  $db = Database::connect();

  function obtenerEstadoBadge($estado) {
    switch ($estado) {
      case 'pendiente': return ['badgeClass' => 'danger', 'texto' => 'Pendiente'];
      case 'en_revision': return ['badgeClass' => 'warning', 'texto' => 'En Revisión'];
      case 'finalizado': return ['badgeClass' => 'success', 'texto' => 'Finalizado'];
      case 'albaranado': return ['badgeClass' => 'primary', 'texto' => 'Albaranado'];
      default: return ['badgeClass' => 'secondary', 'texto' => 'Desconocido'];
    }
  }

  function obtenerNombreUsuario($id, $db) {
    $stmt = $db->prepare("SELECT nombre FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchColumn();
  }

  function obtenerNombreCliente($cliente_id, $db) {
    $stmt = $db->prepare("SELECT CNOM FROM fccli001 WHERE CCODCL = ?");
    $stmt->execute([$cliente_id]);
    return $stmt->fetchColumn();
  }

  // Cambiar estado automáticamente si hay comentarios
  if ($ticket['estado'] === 'pendiente' && !empty($comentarios)) {
    $ticket['estado'] = 'en_revision';
  }
?>

<div class="container-fluid mt-4">
  <div class="card p-4 shadow-sm">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="mb-0">Editar Ticket #<?= htmlspecialchars($ticket['id']) ?></h2>
      <div class="d-flex align-items-center">
        <span class="badge bg-<?= obtenerEstadoBadge($ticket['estado'])['badgeClass'] ?> me-2">
          <?= obtenerEstadoBadge($ticket['estado'])['texto'] ?>
        </span>
        <button type="button" id="btnToggleCronometro" class="btn btn-primary ms-2" data-id="<?= $ticket['id'] ?>" title="Mostrar cronómetro">
          <i class="bi bi-clock"></i>
        </button>
      </div>
    </div>

    <div>
      <strong>Creado por:</strong> <?= htmlspecialchars(obtenerNombreUsuario($ticket['usuario_creador_id'], $db)) ?>
    </div>
    <div class="mb-3">
      <strong>Fecha de creación:</strong> <?= htmlspecialchars($ticket['fecha_inicio']) ?>
    </div>

    <!-- Formulario -->
    <form id="formEditarTicket" method="POST" action="javascript:void(0);">
      <input type="hidden" name="id" value="<?= $ticket['id'] ?>">
      <input type="hidden" name="estado" value="<?= $ticket['estado'] ?>">
      <input type="hidden" id="comentarioId" />

      <div id="mensaje" class="alert d-none mt-3"></div>

      <!-- Cliente -->
      <div class="mb-3">
        <label class="form-label">Cliente</label>
        <div class="d-flex align-items-center">
          <input type="text" class="form-control me-2" value="<?= htmlspecialchars(obtenerNombreCliente($ticket['cliente_id'], DatabaseXGEST::connect())) ?>" readonly>
          <button type="button" class="btn btn-primary" id="verInfoCliente"
            data-nombre="<?= htmlspecialchars($datosCliente['CNOM']) ?>"
            data-telefono="<?= htmlspecialchars($datosCliente['CTEL1']) ?>"
            data-dni="<?= htmlspecialchars($datosCliente['CDNI']) ?>"
            data-email="<?= htmlspecialchars($datosCliente['CMAIL1']) ?>"
            data-direccion="<?= htmlspecialchars($datosCliente['CDOM']) ?>"
            data-ciudad="<?= htmlspecialchars($datosCliente['CPOB']) ?>"
            data-cp="<?= htmlspecialchars($datosCliente['CCODPO']) ?>"
            data-provincia="<?= htmlspecialchars($datosCliente['CPAIS']) ?>">
            <i class="bi bi-eye"></i>
          </button>
        </div>
      </div>

      <!-- Medio y Asignaciones -->
      <div class="row">
        <div class="col-md-8 mb-3">
          <label for="medio_comunicacion" class="form-label">Medio de Comunicación</label>
          <select id="medio_comunicacion" name="medio_comunicacion" class="form-select" required>
            <option value="">Seleccione un medio</option>
            <?php foreach ($medios_comunicacion as $medio): ?>
              <option value="<?= $medio['id'] ?>" <?= $ticket['medio_id'] == $medio['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($medio['nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label">Asignaciones</label>
          <div>
            <?php
              $ticket_id = $ticket['id'];
              if (isset($asignacionesPorTicket[$ticket_id])) {
                foreach ($asignacionesPorTicket[$ticket_id] as $asignacion) {
                  $tipo = $asignacion['tipo'];
                  $nombre = '';
                  if ($tipo === 'tecnico') {
                    $nombre = obtenerNombreUsuario($asignacion['ref_id'], $db);
                    echo '<span class="badge bg-dark me-1">' . htmlspecialchars($nombre) . '</span>';
                  } elseif ($tipo === 'departamento') {
                    $stmt = $db->prepare("SELECT nombre FROM departamentos WHERE id = ?");
                    $stmt->execute([$asignacion['ref_id']]);
                    $nombre = $stmt->fetchColumn();
                    echo '<span class="badge bg-primary me-1">' . htmlspecialchars($nombre) . '</span>';
                  }
                }
              } else {
                echo '<span class="badge bg-danger me-1">Pendiente de asignar</span>';
              }
            ?>
          </div>
        </div>
      </div>

      <!-- Descripción -->
      <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea id="descripcion" name="descripcion" class="form-control" rows="6" required><?= htmlspecialchars($ticket['descripcion']) ?></textarea>
      </div>

      <!-- Lista de Comentarios -->
      <?php if (!empty($comentarios)): ?>
        <div class="mb-4">
          <label class="form-label fw-bold">Comentarios</label>
          <ul class="list-group" id="listaComentarios">
            <?php foreach ($comentarios as $comentario): ?>
              <?php
                $tiempo_aplicado = '';
                if (!empty($comentario['hora_inicio']) && !empty($comentario['hora_fin'])) {
                  try {
                    $inicio = new DateTime($comentario['hora_inicio']);
                    $fin = new DateTime($comentario['hora_fin']);
                    $intervalo = $inicio->diff($fin);
                    $tiempo_aplicado = $intervalo->format('%H:%I');
                  } catch (Exception $e) {}
                }
                $contenido = isset($comentario['contenido']) ? trim($comentario['contenido']) : '';
                $fecha_formateada = date('d-m-Y', strtotime($comentario['fecha']));
              ?>
              <li class="comentario-postit position-relative mb-3 p-3" data-id="<?= $comentario['id'] ?>">
                <div class="top-derecha">
                  <?php if ($tiempo_aplicado): ?>
                    <span class="badge bg-info text-dark"><i class="bi bi-clock"></i> <?= $tiempo_aplicado ?></span>
                  <?php endif; ?>
                  <span class="badge bg-secondary"><?= ucfirst($comentario['tipo']) ?></span>
                  <button class="btn btn-sm btn-outline-primary btn-editar-comentario" title="Editar comentario">
                    <i class="bi bi-pencil"></i>
                  </button>
                </div>

                <small class="text-muted d-block mb-2">
                  <?= $fecha_formateada ?> — <?= htmlspecialchars($comentario['nombre_usuario'] ?? 'Usuario desconocido') ?>
                </small>

                <div class="comentario-contenido">
                  <?php if (!empty($contenido)): ?>
                    <?= $contenido ?>
                  <?php else: ?>
                    <div class="alert alert-secondary d-inline-flex align-items-center p-2 small" role="alert">
                      <i class="bi bi-info-circle me-2"></i>
                      <span>Se ha iniciado un comentario pero aún no se especificó detalle.</span>
                    </div>
                  <?php endif; ?>
                </div>

                <textarea class="form-control mt-2 d-none comentario-editor" rows="3"><?= htmlspecialchars($comentario['contenido'] ?? '') ?></textarea>
                <input type="hidden" class="hora-fin-oculta" value="<?= htmlspecialchars($comentario['hora_fin'] ?? '') ?>">
                <div class="mt-2 d-none comentario-acciones">
                  <button class="btn btn-sm btn-success guardar-edicion" data-bs-toggle="modal" data-bs-target="#modalHoraFin">Guardar</button>
                  <button class="btn btn-sm btn-secondary cancelar-edicion">Cancelar</button>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <!-- Acciones -->
      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-save me-1"></i> Guardar cambios
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modales -->
<?php include_once __DIR__ . '/components/modal/modal_client_info.php'; ?>
<?php include_once __DIR__ . '/components/modal/cronometro_panel.php'; ?>
<?php include_once __DIR__ . '/components/modal/modal_comentario_editor.php'; ?>
<?php include_once __DIR__ . '/components/modal/modal_editar_comentario_hora.php'; ?>

<!-- Scripts -->
<script>
  window.comentarioActivo = <?= json_encode($comentarios_activos[0] ?? null) ?>;
</script>
<script src="/public/js/tickets/tickets_editar.js"></script>
<script src="/public/js/modals/modal_client_info.js"></script>
<script src="/public/js/modals/cronometro_panel.js"></script>
<script src="/public/js/modals/comentario_editor.js"></script>
<script src="/public/js/tickets/comentarios_editar.js"></script>
