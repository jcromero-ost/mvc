<div class="container-fluid mt-2">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Historial de clientes</h2>
    <a href="/crear_cliente" class="btn btn-primary">
      <i class="bi bi-person-plus-fill"></i> Crear nuevo cliente
    </a>
  </div>

   <div class="row g-3 align-items-end mb-4">
    <div class="col-md-12">
        <label for="cliente_historial" class="form-label">Cliente</label>
        <div class="d-flex align-items-center">
          <input type="text" class="form-control me-2" id="cliente_historial" name="cliente_historial" required readonly>
          <button class="btn btn-outline-secondary btn-lupa" type="button" id="botonBuscarCliente">
            <i class="bi bi-search"></i>
          </button>
        </div>
    </div>

    <div class="row g-3 align-items-end mb-4 ms-1">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">
                    Tickets
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">
                    Pedidos
                </button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <!-- Aquí pones el contenido que quieres que aparezca al hacer clic en "Tickets" -->
                <p>Contenido de Tickets</p>
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <!-- Aquí pones el contenido que quieres que aparezca al hacer clic en "Pedidos" -->
                <p>Contenido de Pedidos</p>
            </div>
        </div>
    </div>

  <div class="row g-3 align-items-end mb-4">
    <div class="col-md-3">
      <label for="filtrar_nombre" class="form-label">Nombre</label>
      <input type="text" id="filtrar_nombre" class="form-control">
      <div id="sugerencias-nombre" class="list-group"></div>
    </div>

    <div class="col-md-1">
      <label for="filtrar_id" class="form-label">ID</label>
      <input type="text" id="filtrar_id" class="form-control">
    </div>

    <div class="col-md-2">
      <label for="filtrar_telefono" class="form-label">Telefono</label>
      <input type="text" id="filtrar_telefono" class="form-control">
    </div>

    <div class="col-md-2">
      <label for="filtrar_dni" class="form-label">DNI</label>
      <input type="text" id="filtrar_dni" class="form-control">
    </div>

    <div class="col-md-2">
      <label for="cantidad" class="form-label">Registros por página</label>
      <select id="cantidad" class="form-select">
        <option value="10" selected>10</option>
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
    <table id="tabla_clientes" class="table table-hover align-middle small">
      <thead class="table-dark">
        <tr>
          <th>Nombre</th>
          <th>ID</th>
          <th>Telefono</th>
          <th>DNI</th>
          <th>Direccion</th>
          <th>Ciudad</th>
          <th>CP</th>
          <th>Provincia</th>
          <th class="text-center">Seleccionar</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($clientes as $cliente): ?>
            <tr>
                <td><?= htmlspecialchars($cliente['CNOM']) ?></td>
                <td><?= htmlspecialchars($cliente['CCODCL']) ?></td>
                <td><?= htmlspecialchars($cliente['CTEL1']) ?></td>
                <td><?= htmlspecialchars($cliente['CDNI']) ?></td>
                <td><?= htmlspecialchars($cliente['CDOM']) ?></td>
                <td><?= htmlspecialchars($cliente['CPOB']) ?></td>
                <td><?= htmlspecialchars($cliente['CCODPO']) ?></td>
                <td><?= htmlspecialchars($cliente['CPAIS']) ?></td>
                                                                                                                                                                      
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-primary me-1 btn-seleccionar"
                        data-cliente='<?= json_encode($cliente, JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) ?>'
                        title="Seleccionar">
                        <i class="bi bi-arrow-up"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div id="paginacion" class="mt-3"></div>

  <!-- Modal editar usuario -->
  <?php include_once __DIR__ . '/components/modal_editar_usuario.php'; ?>


<!-- Modal confirmación de eliminación -->
<div class="modal fade" id="modalEliminarUsuario" tabindex="-1" aria-labelledby="modalEliminarUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="/controllers/UsuarioController.php">
        <input type="hidden" name="accion" value="eliminar">
        <input type="hidden" name="id" id="delete_id">

        <div class="modal-header">
          <h5 class="modal-title" id="modalEliminarUsuarioLabel">Eliminar usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <p>¿Estás seguro que deseas eliminar al usuario <strong id="delete_nombre"></strong>?</p>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Eliminar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

</div>
<script src="/public/js/clientes/clientes.js"></script>
<script src="/public/js/clientes/clientes_filtrado.js"></script>


