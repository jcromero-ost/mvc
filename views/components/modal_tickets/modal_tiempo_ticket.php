<!-- Checkbox oculto para el menú -->
<input type="checkbox" id="navbar-toggle" class="d-none" />

<!-- Icono para mostrar el botón -->
<div class="icon-container position-fixed bottom-0 end-0 m-4 z-3">
  <label for="navbar-toggle" class="icon btn btn-secondary rounded-circle text-white fs-4">
    <i class="bi bi-clock"></i>
  </label>
</div>

<!-- Botón para desplegar el menú -->
<div class="btn-menu-container position-fixed bottom-0 end-50 me-5 z-3 opacity-0 transition-opacity">

</div>

<!-- Menú -->
<div class="nav-menu p-3 position-fixed end-0 top-0 bg-dark text-white w-25 h-100 z-1 d-none flex-column mt-5 gap-3">
  
  <!-- Recuadro con el tiempo -->
  <div id="timer" class="fs-4 bg-secondary rounded text-center py-2 mt-5">
    <p class="m-1">00:00:00</p>
  </div>

  <!-- Botones del cronómetro -->
  <div class="d-flex gap-2">
    <button id="iniciarBtn" class="btn btn-primary w-50 btn-sm">Iniciar</button>
    <button id="detenerBtn" class="btn btn-danger w-50 btn-sm">Detener</button>
  </div>

  <!-- Botón para agregar registro -->
  <button id="agregarRegistroBtn" class="btn btn-secondary text-white w-100 d-flex align-items-center justify-content-center gap-2 btn-sm">
    <i class="bi bi-plus"></i>
    Agregar Registro
  </button>

  <!-- Formulario oculto -->
  <div id="formularioTiempo" class="bg-dark p-2 rounded" style="display: none;">
    <label for="registroHoraInicio" class="form-label text-white small mb-1">Hora Inicio</label>
    <input type="time" id="registroHoraInicio" name="registroHoraInicio" class="form-control form-control-sm mb-2" required>

    <label for="registroHoraFin" class="form-label text-white small mb-1">Hora Fin</label>
    <input type="time" id="registroHoraFin" name="registroHoraFin" class="form-control form-control-sm mb-2" required>

    <button id="guardarRegistro" class="btn btn-success w-100 btn-sm">Guardar</button>
  </div>

  <!-- Tabla de últimos registros -->
  <div id="ultimasModificaciones" class="bg-dark p-2 rounded">
    <h3 class="fs-6 text-white mb-2">Últimos Registros</h3>
    <div class="overflow-auto">
      <table class="table table-sm table-bordered text-white mb-0">
        <thead class="table-secondary text-dark">
          <tr>
            <th>Tiempo Total</th>
            <th>Usuario</th>
          </tr>
        </thead>
        <tbody id="tablaModificaciones">
          <!-- Las filas se agregan dinámicamente -->
        </tbody>
      </table>
    </div>
  </div>

  <!-- Botón final -->
  <button id="botonTraspasarTicket" class="btn btn-secondary btn-sm w-100">Traspasar Ticket</button>
</div>

<style>
  #navbar-toggle:checked + .icon-container + .btn-menu-container + .nav-menu {
    display: flex !important;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('agregarRegistroBtn').addEventListener('click', function () {
      const formulario = document.getElementById('formularioTiempo');
      formulario.style.display = (formulario.style.display === 'none' || formulario.style.display === '') ? 'block' : 'none';
    });
  });
</script>
