<!-- Modal confirmación de eliminación -->
<div class="modal modal-xl fade" id="modalDescripcionCompleta" tabindex="-1" aria-labelledby="modalDescripcionCompletaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEliminarUsuarioLabel">Descripción Completa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <p id="descripcionCompleta_texto"></p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary">Editar Ticket</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
    </div>
  </div>
</div>

<script src="/public/js/tickets/tickets_modal_descripcion.js"></script>
