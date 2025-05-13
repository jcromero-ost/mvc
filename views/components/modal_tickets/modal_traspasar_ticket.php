<!-- Modal confirmación de eliminación -->
<div class="modal fade" id="modalTraspasarTicket" tabindex="-1" aria-labelledby="modalTraspasarTicketLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTraspasarTicketLabel">Traspasar ticket</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <p>Indica el técnico al que quieres traspasar el ticket</p>
            <select id="tecnico_modal" name="tecnico_modal" class="form-select" required>
                <option value="">Seleccione un técnico</option>
                <?php foreach ($tecnicos as $tec): ?>
                    <option value="<?= $tec['id'] ?>" <?= ($ticket['tecnico_id'] == $tec['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tec['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="modal-footer">
          <button id="botonConfirmTraspaso" type="button" data-bs-dismiss="modal" class="btn btn-primary">Traspasar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
    </div>
  </div>
</div>

