<!-- Modal para editar comentario con Quill -->
<div class="modal fade" id="modalEditarComentario" tabindex="-1" aria-labelledby="modalEditarComentarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="modalEditarComentarioLabel">Editar Comentario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div id="editorComentario" style="height: 250px;"></div>

        <button type="button" id="btnEditarHoras" class="btn btn-outline-primary mt-3">
          Editar Horas Manualmente
        </button>

        <div id="contenedorHoras" class="d-none mt-3">
          <div class="row g-2">
            <div class="col-md-4">
              <label for="horaInicioManual" class="form-label">Hora de Inicio</label>
              <input type="time" id="horaInicioManual" class="form-control">
            </div>
            <div class="col-md-4">
              <label for="horaFinManual" class="form-label">Hora de Fin</label>
              <input type="time" id="horaFinManual" class="form-control">
            </div>
            <div class="col-md-4">
              <label for="fechaManual" class="form-label">Fecha</label>
              <input type="date" id="fechaManual" class="form-control">
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" id="btnGuardarComentario" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>
