<?php /* Modal información completa de cliente */ ?>
<div class="modal fade" id="modalClienteInfo" tabindex="-1" aria-labelledby="modalClienteInfoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalClienteInfoLabel">Información del Cliente</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body" id="cliente-info-content">
        <?php include __DIR__ . '/../cliente/info_cliente_content.php'; ?>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i> Cerrar
        </button>
      </div>

    </div>
  </div>
</div>

<script src="/public/js/modals/modal_client_info.js"></script>
