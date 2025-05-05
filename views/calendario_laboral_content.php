

<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
<h2 class="mb-2">
  <i class="bi bi-calendar3 me-2"></i>Calendario Laboral
</h2>
  <!-- Navegación de periodo (mensual/semanal/anual) -->
  <div id="barraNavegacion" class="d-flex align-items-center gap-2 flex-wrap">
    <button id="btnHoy" class="btn btn-outline-dark btn-sm rounded-pill px-3">Hoy</button>

    <button id="btnMesAnterior" class="btn btn-outline-secondary btn-sm rounded-circle px-2">
      <i class="bi bi-chevron-left"></i>
    </button>

    

    <button id="btnMesSiguiente" class="btn btn-outline-secondary btn-sm rounded-circle px-2">
      <i class="bi bi-chevron-right"></i>
    </button>

    <h5 id="tituloMes" class="mb-0"></h5>
  </div>

  <!-- Selector de vista -->
  <div class="form-group">
    <select id="modoVista" class="form-select form-select-sm w-auto" title="Cambiar vista">
      <option value="mensual" selected>Vista mensual</option>
      <option value="anual">Vista anual</option>
      <option value="semanal">Vista semanal</option>
    </select>
  </div>
</div>



<div class="card shadow-sm">
  <div class="card-body">

    

    <!-- Contenedor de vistas -->
    <div id="vistaMensual"><?php include __DIR__ . '/calendario_laboral_mensual.php'; ?></div>
    <div id="vistaAnual" class="d-none"><?php include __DIR__ . '/calendario_laboral_anual.php'; ?></div>
    <div id="vistaSemanal" class="d-none"><?php include __DIR__ . '/calendario_laboral_semanal.php'; ?></div>

  </div>
</div>


<script type="module" src="/public/js/calendario/index.js"></script>
