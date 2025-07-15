<!-- Modal confirmación de revisar solicitud -->
<div class="modal fade" id="modalRechazarSolicitud" tabindex="-1" aria-labelledby="modalRechazarSolicitudLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="/vacaciones_update_estado">
        <input type="hidden" name="id" id="form-id-rechazar">
        <input type="hidden" name="estado" id="form-estado-rechazar">
        <input type="hidden" name="rechazo_motivo" id="form-rechazo-motivo-rechazar">

        <div class="modal-header">
          <h5 class="modal-title" id="modalRechazarSolicitudLabel">Rechazar Solicitud</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="motivo_modal_rechazar" class="form-label">Motivo</label>
                    <textarea id="motivo_modal_rechazar" name="motivo_modal_rechazar" class="form-control" required></textarea>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button id="botonConfirmarRechazo" type="submit" class="btn btn-danger">Confirmar rechazo</button>
        </div>
      </form>
    </div>
  </div>
</div>


