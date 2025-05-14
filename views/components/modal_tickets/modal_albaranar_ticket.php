<div class="modal fade" id="modalAlbaranarTicket" tabindex="-1" aria-labelledby="modalAlbaranarTicketLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form id="formCrearCliente" method="POST"  action="/store_cliente_ticket" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="modalAlbaranarTicketLabel">Albaranar ticket</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="tiempo_empleado_albaranar" class="form-label">Tiempo empleado</label>
                    <input type="text" class="form-control" id="tiempo_empleado_albaranar" name="tiempo_empleado_albaranar" required readonly value="<?= htmlspecialchars($tiempoFormateado) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="cliente_albaranar" class="form-label">Cliente</label>
                    <input type="text" class="form-control d-none" id="cliente_albaranar" name="cliente_albaranar" value="<?= htmlspecialchars($ticket['cliente_id']) ?>">
                    <input type="text" class="form-control" value="<?= htmlspecialchars(obtenerNombreCliente($ticket['cliente_id'], $db)) ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label for="fecha_albaranar" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="fecha_albaranar" name="fecha_albaranar" value="<?php echo date('Y-m-d'); ?>" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="codigo_articulo_albaranar" class="form-label">Código de artículo</label>
                    <input type="text" class="form-control" id="codigo_articulo_albaranar" name="codigo_articulo_albaranar" required>
                </div>

                <div class="col-md-3">
                    <label for="cantidad_albaranar" class="form-label">Cantidad</label>
                    <input type="number" class="form-control" id="cantidad_albaranar" name="cantidad_albaranar" required>
                </div>

                <div class="col-md-3">
                    <label for="precio_albaranar" class="form-label">Precio/Hora</label>
                    <input type="number" class="form-control" id="precio_albaranar" name="precio_albaranar" value="28.92" readonly required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="descripcion_amplia_albaranar" class="form-label">Descripción</label>
                    <div class="input-group">
                    <textarea class="form-control" id="descripcion_amplia_albaranar" name="descripcion_amplia_albaranar" rows="10" required></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
          <button id="botonConfirmAlbaranar" type="button" class="btn btn-success btn-primary" data-bs-dismiss="modal">Confirmar albarán</button>
          <button type="button" class="btn btn-secondary btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="/public/js/tickets/tickets_modal_albaranar.js"></script>
