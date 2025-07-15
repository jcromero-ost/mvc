<div class="modal fade" id="modalSeleccionarCliente" tabindex="-1" aria-labelledby="modalSeleccionarClienteLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalSeleccionarClienteLabel">Buscar cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label for="busquedaCliente" class="form-label">Nombre o teléfono</label>
          <input type="text" class="form-control" id="busquedaCliente" placeholder="Escriba al menos 3 caracteres...">
          <div id="sugerenciasCliente" class="list-group position-absolute w-100 mt-1 z-3 d-none"></div>
        </div>

        <div class="table-responsive">
          <table class="table table-hover small" id="tablaSugerenciasCliente">
            <thead class="table-dark">
              <tr>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>DNI</th>
                <th>Dirección</th>
                <th class="text-center">Seleccionar</th>
              </tr>
            </thead>
            <tbody id="tablaResultadosCliente">
              <!-- Resultados dinámicos -->
            </tbody>
          </table>
        </div>

        <nav>
          <ul class="pagination justify-content-center mt-3" id="paginacionClientes">
            <!-- Paginación dinámica -->
          </ul>
        </nav>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Aceptar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<script>
  const clientes = <?= json_encode($clientes, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
</script>
<script src="/public/js/tickets/tickets_modal_cliente.js" defer></script>
