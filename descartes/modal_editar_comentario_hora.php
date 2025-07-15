<div class="modal fade" id="modalFinalizarComentario" tabindex="-1" aria-labelledby="modalFinalizarComentarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalFinalizarComentarioLabel">Finalizar comentario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p>¿Deseas usar la hora actual como hora de finalización?</p>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="opcionHora" id="usarHoraActual" value="actual" checked>
          <label class="form-check-label" for="usarHoraActual">Usar hora actual</label>
        </div>
        <div class="form-check mt-2">
          <input class="form-check-input" type="radio" name="opcionHora" id="introducirHora" value="manual">
          <label class="form-check-label" for="introducirHora">Introducir hora manualmente</label>
        </div>
        <input type="time" id="horaManualInput" class="form-control mt-3 d-none">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="confirmarFinalizarComentario">Confirmar</button>
      </div>
    </div>
  </div>
</div>
