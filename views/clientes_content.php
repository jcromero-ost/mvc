<div class="container-fluid mt-2">
  <div id="mensaje" class="text-success mt-2 d-none alert" role="alert"></div>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Listado de clientes</h2>
    <a href="/crear_cliente" class="btn btn-primary">
      <i class="bi bi-person-plus-fill"></i> Crear nuevo cliente
    </a>
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
          <th class="text-center">Acciones</th>
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
                <button type="button" class="btn btn-sm btn-primary me-1 btn-editar"
                    data-cliente='<?= json_encode($cliente, JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) ?>'
                    title="Editar">
                    <i class="bi bi-pencil-square"></i>
                </button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div id="paginacion" class="mt-3"></div>

  <!-- Modal descripcion -->
  <?php include_once __DIR__ . '/components/modal_clientes/modal_editar_cliente.php'; ?>

</div>
<script src="/public/js/clientes/clientes.js"></script>
<script src="/public/js/clientes/clientes_editar.js"></script>


