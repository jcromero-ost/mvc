<div class="container mt-5">
  <h2 class="text-center">Crear nuevo ticket</h2>
  <p class="form-control-plaintext text-center mb-4" id="fecha_inicio">
    <?= date('d-m-Y H:i') ?>
  </p>

  <form method="POST" action="/store_ticket" id="formTicket">
    <div class="row">
      <!-- Cliente -->
      <div class="mb-3 col-md-6 position-relative">
        <label for="cliente_search" class="form-label">Buscar cliente</label>
        <input type="text" class="form-control" id="cliente_search" placeholder="Nombre, teléfono o DNI" autocomplete="off" required>
        <input type="hidden" name="cliente_id" id="cliente_id">
        <div id="cliente_suggestions" class="list-group mt-1" style="position: absolute; z-index: 1000;"></div>
      </div>

      <!-- Medio de comunicación -->
      <div class="mb-3 col-md-6">
        <label for="medio_comunicacion" class="form-label">Medio de comunicación</label>
        <select id="medio_comunicacion" name="medio_comunicacion" class="form-select" required>
          <option value="">Seleccione un medio</option>
          <?php foreach ($medios_comunicacion as $medio): ?>
            <option value="<?= $medio['id'] ?>"><?= htmlspecialchars($medio['nombre']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

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
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="tipo_asignacion" id="sin_asignar" value="ninguno">
          <label class="form-check-label" for="sin_asignar">Sin asignar</label>
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
    
    <!-- Descripción -->
    <div class="mb-2">
      <label for="descripcion" class="form-label">Descripción</label>
      <textarea class="form-control" id="descripcion" name="descripcion" rows="5" required placeholder="Describa brevemente la incidencia..."></textarea>
    </div>
    <p class="form-control-plaintext text-center mb-2"><strong>Creador del ticket:</strong> <?= $_SESSION['nombre'] ?></p>

    <!-- Botón de guardar -->
    <div class="text-center">
      <button type="submit" class="btn btn-primary px-4 py-2">Guardar ticket</button>
    </div>
  </form>
</div>

<script src="/public/js/tickets/crear_ticket.js" defer></script>
