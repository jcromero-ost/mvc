<!-- Modal confirmación de revisar solicitud -->
<div class="modal fade" id="modalRevisarSolicitud" tabindex="-1" aria-labelledby="modalRevisarSolicitudLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="/vacaciones_update_estado">
        <input type="hidden" name="id" id="form-id">
        <input type="hidden" name="estado" id="form-estado">
        <input type="hidden" name="rechazo_motivo" id="form-rechazo-motivo">

        <div class="modal-header">
          <h5 class="modal-title" id="modalRevisarSolicitudLabel">Revisar Solicitud</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="fecha_inicio_modal" class="form-label">Fecha Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio_modal" name="fecha_inicio_modal" readonly>
                </div>

                <div class="col-md-6">
                    <label for="fecha_fin_modal" class="form-label">Fecha Fin</label>
                    <input type="date" class="form-control" id="fecha_fin_modal" name="fecha_fin_modal" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="motivo_modal" class="form-label">Motivo</label>
                    <textarea id="motivo_modal" name="motivo_modal" class="form-control" readonly></textarea>
                </div>
            </div>
        </div>

        <div class="modal-footer">
          <button id="botonAceptarSolicitud" type="submit" class="btn btn-success">Aceptar</button>
          <button id="botonRechazarSolicitud" type="button" class="btn btn-danger">Rechazar</button>
        </div>
      </form>
    </div>
  </div>
</div>


