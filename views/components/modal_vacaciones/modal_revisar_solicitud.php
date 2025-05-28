<!-- Modal confirmación de revisar solicitud -->
<div class="modal fade" id="modalRevisarSolicitud" tabindex="-1" aria-labelledby="modalRevisarSolicitudLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="/controllers/TicketController.php">
        <input type="hidden" name="accion" value="eliminar">
        <input type="hidden" name="id" id="delete_id">

        <div class="modal-header">
          <h5 class="modal-title" id="modalRevisarSolicitudLabel">Revisar Solicitud</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                </div>

                <div class="col-md-6">
                    <label for="fecha_fin" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="motivo" class="form-label">Motivo</label>
                    <textarea id="motivo" name="motivo" class="form-control"></textarea>
                </div>
            </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success" data-bs-dismiss="modal">Aceptar</button>
          <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Rechazar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="/public/js/tickets/tickets_modal_eliminar.js"></script>
