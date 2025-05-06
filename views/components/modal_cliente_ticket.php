<div class="modal fade" id="modalSeleccionarCliente" tabindex="-1" aria-labelledby="modalSeleccionarClienteLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title" id="modalSeleccionarClienteLabel">Lista de clientes</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        
        <div class="modal-body">
            <div class="container-fluid">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <input type="text" id="seleccionCliente" class="form-control me-2" readonly>
                    <a class="btn btn-primary btn-crear-cliente">
                        <i class="bi bi-person-plus-fill"></i>
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
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-success btn-primary" data-bs-dismiss="modal">Guardar cambios</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>

    </div>
  </div>
</div>

<?php include_once __DIR__ . '/modal_crear_cliente_ticket.php'; ?>


<script src="/public/js/clientes/clientes.js"></script>
<script src="/public/js/tickets/tickets_modal_cliente.js"></script>

