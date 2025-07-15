<!-- Modal Comentario con Editor Quill -->
<div class="modal fade" id="modalComentario" tabindex="-1" aria-labelledby="modalComentarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalComentarioLabel">
          <i class="bi bi-chat-left-text me-2"></i>Nuevo Comentario
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <!-- Contenedor del editor Quill -->
        <div id="editorQuill" style="height: 200px;"></div>

        <!-- Campo oculto para guardar contenido HTML generado por Quill -->
        <input type="hidden" name="comentario_quill" id="comentario_quill">
      </div>

      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-1"></i> Cancelar
        </button>
        <button type="button" class="btn btn-primary" id="guardarComentario">
          <i class="bi bi-save me-1"></i> Guardar comentario
        </button>
      </div>

    </div>
  </div>
</div>
