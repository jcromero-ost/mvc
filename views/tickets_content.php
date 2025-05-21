<div class="container-fluid mt-2">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Listado de tickets</h2>
    <a href="/crear_ticket" class="btn btn-success btn-primary">
      <i class="bi bi-clipboard-plus"></i> Crear nuevo ticket
    </a>
  </div>

  <div class="row g-3 align-items-end mb-4">
    <div class="col-md-3">
      <label for="filtrar_cliente" class="form-label">Cliente</label>
      <input type="text" id="filtrar_cliente" class="form-control">
      <div id="sugerencias-nombre" class="list-group"></div>
    </div>

    <div class="col-md-1">
      <label for="filtrar_id" class="form-label">ID</label>
      <input type="text" id="filtrar_id" class="form-control">
    </div>

    <div class="col-md-2">
      <label for="filtrar_tecnico" class="form-label">Técnico</label>
      <select id="filtrar_tecnico" class="form-select">
            <option value="">Seleccione un tecnico</option>
            <option value="Pendiente de asignar">Sin tecnico asignado</option>
            <?php foreach ($tecnicos as $tec): ?>
              <option><?= htmlspecialchars($tec['nombre']) ?></option>
            <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-2">
      <label for="filtrar_estado" class="form-label">Estado</label>
      <select id="filtrar_estado" class="form-select">
            <option value="">Seleccione un estado</option>
            <option value="Pendiente">Pendiente</option>
            <option value="En Revisión">En Revisión</option>
            <option value="Finalizado">Finalizado</option>
            <option value="Albaranado">Albaranado</option>

      </select>
    </div>

    <div class="col-md-2">
      <label for="cantidad" class="form-label">Registros por página</label>
      <select id="cantidad" class="form-select">
        <option value="5" selected>5</option>
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
    </div>

    <div class="col-md-2 d-flex gap-2">
      <button id="btn-filtrar" class="btn btn-primary w-100">
        <i class="bi bi-funnel"></i> Filtrar
      </button>
      <button id="btn-imprimir" class="btn btn-secondary w-100">
        <i class="bi bi-printer"></i> Imprimir
      </button>
    </div>
  </div>

  <div class="table-responsive">
    <table id="tabla_tickets" class="table table-hover align-middle small">
      <thead class="table-dark">
        <tr>
          <th>Cliente</th>
          <th>ID</th>
          <th>Medio</th>
          <th>Técnico</th>
          <th>Fecha Inicio</th>
          <th>Descripcion</th>
          <th>Tiempo</th>
          <th>Estado</th>
          <th class="text-center">Acciones</th>
        </tr>
      </thead>
      <?php
        require_once(__DIR__ . '/../models/Database.php');
        require_once(__DIR__ . '/../models/DatabaseXGEST.php');

        $db = Database::connect();

        $db2 = DatabaseXGEST::connect();

        function obtenerNombreCliente($cliente_id, $db2) {
          $stmt = $db2->prepare("SELECT CNOM FROM fccli001 WHERE CCODCL = ?");
          $stmt->execute([$cliente_id]);
          return $stmt->fetchColumn();
        }

        function obtenerNombreMedio($medio_id, $db) {
          $stmt = $db->prepare("SELECT nombre FROM medios_comunicacion WHERE id = ?");
          $stmt->execute([$medio_id]);
          return $stmt->fetchColumn();
        }

        function obtenerNombreTecnico($tecnico_id, $db) {
          $stmt = $db->prepare("SELECT nombre FROM usuarios WHERE id = ?");
          $stmt->execute([$tecnico_id]);
          return $stmt->fetchColumn();
        }

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
            case 'albaranado':
                $badgeClass = 'primario'; // Verde
                $texto = 'Albaranado';
                break;
            default:
                $badgeClass = 'secondary'; // Gris
                $texto = 'Desconocido';
                break;
            }
            return ['badgeClass' => $badgeClass, 'texto' => $texto];
        }

        function obtenerTiempoTranscurrido($fecha_inicio) {
          // Obtener la fecha actual
          $fecha_actual = new DateTime();
          // Convertir la fecha de inicio a un objeto DateTime
          $fecha_inicio = new DateTime($fecha_inicio);
      
          // Calcular la diferencia entre la fecha actual y la de inicio
          $intervalo = $fecha_actual->diff($fecha_inicio);
          
          // Inicializar el formato de la cadena
          $resultado = '';
      
          // Verificar si hay días
          if ($intervalo->d > 0) {
              $resultado .= $intervalo->d . 'd ';
          }
      
          // Verificar si hay horas
          if ($intervalo->h > 0) {
              $resultado .= $intervalo->h . 'h ';
          }
      
          // Verificar si hay minutos
          if ($intervalo->i > 0 || $resultado === '') { // Mostrar minutos si no hay días ni horas
              $resultado .= $intervalo->i . 'm';
          }
      
          return $resultado;
        }
      ?>
      <tbody>
        <?php foreach ($tickets as $ticket): ?>
            <tr>
                <td><?= htmlspecialchars(obtenerNombreCliente($ticket['cliente_id'], $db2)) ?></td>
                <td><?= htmlspecialchars($ticket['id']) ?></td>
                <td><?= htmlspecialchars(obtenerNombreMedio($ticket['medio_id'], $db)) ?></td>
                <td><?= empty($ticket['tecnico_id']) ? 'Pendiente de asignar' : htmlspecialchars(obtenerNombreTecnico($ticket['tecnico_id'], $db)) ?></td>
                <td><?= htmlspecialchars($ticket['fecha_inicio']) ?></td>
                <td>
                  <?= htmlspecialchars(implode(' ', array_slice(explode(' ', $ticket['descripcion']), 0, 45))) ?>
                  <a 
                    class="btn btn-primary btn-extra-small btn-descripcion-completa" 
                    data-id="<?= $ticket['id'] ?>" 
                    data-descripcion="<?= htmlspecialchars($ticket['descripcion'], ENT_QUOTES) ?>"
                    data-bs-toggle="modal" 
                    data-bs-target="#modalDescripcionCompleta">
                    ...ver más
                  </a>
                </td>
                <td><?= htmlspecialchars(obtenerTiempoTranscurrido($ticket['fecha_inicio'])) ?></td>
                <td>
                <?php
                  // Obtener el estado y los valores correspondientes
                  $estado = $ticket['estado'];
                  $estadoInfo = obtenerEstadoBadge($estado);
                ?>
                <span class="badge bg-<?= $estadoInfo['badgeClass'] ?>">
                  <?= $estadoInfo['texto'] ?>
                </span>
              </td>

                <td class="text-center">
                    <a href="/editar_ticket?id=<?= $ticket['id'] ?>"><button type="button" class="btn btn-sm btn-primary me-1 btn-editar"
                        data-ticket='<?= json_encode($ticket, JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) ?>'
                        title="Editar">
                        <i class="bi bi-pencil-square"></i>
                    </button></a>

                    <button type="button" class="btn btn-sm btn-danger btn-eliminar d-none"
                        data-id="<?= $ticket['id'] ?>"
                        title="Eliminar">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div id="paginacion" class="mt-3"></div>

  <!-- Modal editar ticket -->
  <?php include_once __DIR__ . '/components/modal_tickets/modal_editar_ticket.php'; ?>

  <!-- Modal eliminar ticket -->
  <?php include_once __DIR__ . '/components/modal_tickets/modal_eliminar_ticket.php'; ?>

  <!-- Modal descripcion -->
  <?php include_once __DIR__ . '/components/modal_tickets/modal_descripcion_ticket.php'; ?>

</div>
<script src="/public/js/tickets/tickets_tabla.js"></script>

