<!-- Modal confirmación de eliminación -->
<?php
// Cargar departamentos
$departamentoModel = new Departamento();
$departamentos = $departamentoModel->getAllDepartamentosSinWebmaster();

// Cargar técnicos (asume que hay un método para obtenerlos)
$tecnicos = Usuario::getAllUsuariosSinWebmaster();
?>
<div class="modal fade" id="modalAsignarTicket" tabindex="-1" aria-labelledby="modalAsignarTicketLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="/asignar_ticket">
        <input type="hidden" name="id" id="id">

        <div class="modal-header">
          <h5 class="modal-title" id="modalAsignarTicketLabel">Asignar ticket #</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <div class="row">
            <!-- Técnico asignado -->
            <!-- Tipo de asignación -->
            <div class="mb-3 col-md-6">
              <label class="form-label d-block">Asignación</label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipo_asignacion" id="asignar_departamento" value="departamento" checked>
                <label class="form-check-label" for="asignar_departamento">Departamento</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipo_asignacion" id="asignar_tecnico" value="tecnico">
                <label class="form-check-label" for="asignar_tecnico">Técnico(s)</label>
              </div>
            </div>

            <!-- Select múltiple de departamentos -->
            <div class="mb-3 col-md-6" id="select_departamentos">
              <label class="form-label">Seleccionar departamento(s)</label>
              <select name="departamentos[]" class="form-select" multiple>
                <?php foreach ($departamentos as $dept): ?>
                  <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['nombre']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Select múltiple de técnicos -->
            <div class="mb-3 col-md-6 d-none" id="select_tecnicos">
              <label class="form-label">Seleccionar técnico(s)</label>
              <select name="tecnicos[]" class="form-select" multiple>
                <?php foreach ($tecnicos as $tec): ?>
                  <?php if ($tec['departamento_id'] != 1): // Excluye 'webmaster' ?>
                    <option value="<?= $tec['id'] ?>"><?= htmlspecialchars($tec['nombre']) ?></option>
                  <?php endif; ?>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Asignar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</div>


