<div class="container-fluid mt-2">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Listado de tickets pendientes</h2>
    <a href="/crear_ticket" class="btn btn-success btn-primary">
      <i class="bi bi-clipboard-plus"></i> Crear nuevo ticket
    </a>
  </div>

  <div class="row g-3 align-items-end mb-4">
    <div class="col-md-2">
      <label for="filtrar_cliente" class="form-label">Cliente</label>
      <input type="text" id="filtrar_cliente" class="form-control">
      <div id="sugerencias-nombre" class="list-group"></div>
    </div>

    <div class="col-md-1">
      <label for="filtrar_id" class="form-label">ID</label>
      <input type="text" id="filtrar_id" class="form-control">
    </div>

    <div class="col-md-2">
      <label for="filtrar_medio" class="form-label">Medio</label>
      <select id="filtrar_medio" class="form-select">
        <option value="">Seleccione un medio</option>
        <?php foreach ($medios_comunicacion as $medio): ?>
            <option value="<?= htmlspecialchars($medio['nombre']) ?>"><?= htmlspecialchars($medio['nombre']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-3">
      <div class="d-flex justify-content-center gap-3 mb-2">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="filtro_asignacion" id="filtro_departamento" value="departamento" checked>
          <label class="form-check-label" for="filtro_departamento">Departamento</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="filtro_asignacion" id="filtro_tecnico" value="tecnico">
          <label class="form-check-label" for="filtro_tecnico">Técnico</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="filtro_asignacion" id="filtro_sinAsignar" value="ain_asignar">
          <label class="form-check-label" for="filtro_sinAsignar">Sin asignar</label>
        </div>
      </div>

      <!-- Select de departamentos (visible por defecto) -->
      <select id="select_filtro_departamento" class="form-select">
        <option value="">Seleccione un departamento</option>
        <?php 
        if ($_SESSION['dept'] == '2' || $_SESSION['dept'] == '3') {
            // Mostrar todos los departamentos
            foreach ($departamentos as $dept): 
        ?>
            <option value="<?= htmlspecialchars($dept['nombre']) ?>"><?= htmlspecialchars($dept['nombre']) ?></option>
        <?php 
            endforeach;
        } else {
            // Mostrar solo el departamento del usuario
            foreach ($departamentos as $dept): 
                if ($dept['id'] == $_SESSION['dept']): 
        ?>
            <option value="<?= htmlspecialchars($dept['nombre']) ?>"><?= htmlspecialchars($dept['nombre']) ?></option>
        <?php 
                endif;
            endforeach;
        }
        ?>
      </select>


      <!-- Select de técnicos (oculto al inicio) -->
      <select id="select_filtro_tecnico" class="form-select d-none mt-2">
        <option value="">Seleccione un técnico</option>
        <?php if ($_SESSION['dept'] != '3' && $_SESSION['dept'] != '2'): ?>
            <?php foreach ($tecnicos as $tec): ?>
                <?php if ($tec['id'] == $_SESSION['id']): ?>
                    <option><?= htmlspecialchars($tec['nombre']) ?></option>
                    <?php break; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <?php foreach ($tecnicos as $tec): ?>
                <option><?= htmlspecialchars($tec['nombre']) ?></option>
            <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>


    <div class="col-md-2">
      <label for="filtrar_estado" class="form-label">Estado</label>
      <select id="filtrar_estado" class="form-select">
            <option value="">Seleccione un estado</option>
            <option value="Pendiente">Pendiente</option>
            <option value="En Revisión">En Revisión</option>
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

    <div class="col-md-2 d-flex gap-2 d-none">
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
          <th>Asignaciones</th>
          <th>Fecha Inicio</th>
          <th>Descripcion</th>
<th  id="th-tiempo-abierto" style="cursor:pointer">
  Tiempo Abierto
  <i class="bi bi-arrow-down-up ms-1"></i>
</th>
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

        function formatoTiempo($segundos) {
            $horas = floor($segundos / 3600);
            $minutos = floor(($segundos % 3600) / 60);
            $segundos = $segundos % 60;
            return sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
        }

        function tiempoAbierto($fecha_inicio) {
          $inicio = new DateTime($fecha_inicio);
          $ahora = new DateTime();
          $diferencia = $inicio->diff($ahora);

          $dias = $diferencia->d + $diferencia->m * 30 + $diferencia->y * 365;
          $horas = $diferencia->h;
          $minutos = $diferencia->i;
          $segundos = $diferencia->s;

          return sprintf('%dd %02dh %02dm', $dias, $horas, $minutos);
        }
      ?>
      <tbody>
        <?php foreach ($tickets as $ticket): ?>
            <tr>
                <td><?= htmlspecialchars(obtenerNombreCliente($ticket['cliente_id'], $db2)) ?></td>
                <td><?= htmlspecialchars($ticket['id']) ?></td>
                <td><?= htmlspecialchars(obtenerNombreMedio($ticket['medio_id'], $db)) ?></td>
                <td>
                  <?php
                    $ticket_id = $ticket['id'];
                    if (isset($asignacionesPorTicket[$ticket_id])) {
                        foreach ($asignacionesPorTicket[$ticket_id] as $asignacion) {
                            $tipo = $asignacion['tipo'];
                            $nombre = '';

                            // Busca nombre según el tipo de asignación
                            if ($tipo === 'tecnico') {
                                $nombre = obtenerNombreTecnico($asignacion['ref_id'], $db);
                                echo '<span class="badge bg-dark me-1">' . htmlspecialchars($nombre) . '</span>';
                            } elseif ($tipo === 'departamento') {
                                // Suponiendo que tengas una función o consulta para obtener nombre de departamento
                                $stmt = $db->prepare("SELECT nombre FROM departamentos WHERE id = ?");
                                $stmt->execute([$asignacion['ref_id']]);
                                $nombre = $stmt->fetchColumn();
                                echo '<span class="badge bg-primario me-1">' . htmlspecialchars($nombre) . '</span>';
                            }
                        }
                    } else {
                      echo '<span class="badge bg-danger me-1">Pendiente de asignar</span>';
                    }
                  ?>
                </td>
                <td><?= htmlspecialchars($ticket['fecha_inicio']) ?></td>
                <?php
                  $descripcion = $ticket['descripcion'];
                  $palabras = explode(' ', $descripcion);
                  $descripcion_resumida = implode(' ', array_slice($palabras, 0, 45));
                ?>
                <td>
                  <?= htmlspecialchars($descripcion_resumida) ?>
                  <?php if (count($palabras) > 45): ?>
                    <a 
                      class="btn btn-primary btn-extra-small btn-descripcion-completa" 
                      data-id="<?= $ticket['id'] ?>" 
                      data-descripcion="<?= htmlspecialchars($descripcion, ENT_QUOTES) ?>"
                      data-bs-toggle="modal" 
                      data-bs-target="#modalDescripcionCompleta">
                      ...ver más
                    </a>
                  <?php endif; ?>
                </td>
                <td><?= tiempoAbierto($ticket['fecha_inicio']) ?></td>
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
                    <a href="/edit_ticket?id=<?= $ticket['id'] ?>"><button type="button" class="btn btn-sm btn-primary me-1 btn-editar"
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
  

  <!-- Modal descripcion -->
  <?php include_once __DIR__ . '/components/modal_tickets/modal_descripcion_ticket.php'; ?>

</div>
<script src="/public/js/tickets/tickets_tabla.js"></script>

