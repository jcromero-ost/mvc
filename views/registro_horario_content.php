<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="vh-container d-flex justify-content-center align-items-center">
  <div class="card p-5 shadow text-center w-100" style="max-width: 850px; min-height: 70vh;">
    <h2 class="mb-4">Control de Horas</h2>

    <!-- Reloj actual -->
    <div id="reloj" class="display-4 mb-3">00:00:00</div>

    <!-- Cronómetro de jornada -->
    <h6 id="estado-cronometro" class="text-muted">Esperando inicio...</h6>
    <div id="cronometro" class="mb-4 text-primary fw-bold">00:00:00</div>

    <!-- Botones de acción estilizados -->
    <div class="d-flex flex-wrap justify-content-center gap-3 mb-4">
        <button id="btn-iniciar-jornada" class="btn btn-custom" style="background-color: var(--color-success); color: white;">
            <i class="bi bi-play-fill icono-boton"></i>
            <div class="texto-boton">Iniciar<br>jornada</div>
        </button>

        <button id="btn-iniciar-descanso" class="btn btn-custom d-none" style="background-color: var(--color-warning); color: black;">
            <i class="bi bi-cup-hot-fill icono-boton"></i>
            <div class="texto-boton">Iniciar<br>descanso</div>
        </button>

        <button id="btn-finalizar-descanso" class="btn btn-custom d-none" style="background-color: var(--color-primary); color: white;">
            <i class="bi bi-arrow-return-left icono-boton"></i>
            <div class="texto-boton">Finalizar<br>descanso</div>
        </button>

        <button id="btn-finalizar-jornada" class="btn btn-custom d-none" style="background-color: var(--color-danger); color: white;">
            <i class="bi bi-stop-fill icono-boton"></i>
            <div class="texto-boton">Finalizar<br>jornada</div>
        </button>
    </div>

    <!-- Último registro -->
    <h6>Último registro</h6>
    <div class="text-start">
        <p><strong>Hora de inicio:</strong> <span id="hora-inicio">--:--:--</span></p>
        <p><strong>Hora de fin:</strong> <span id="hora-fin">--:--:--</span></p>
        <p><strong>Tiempo trabajado:</strong> <span id="tiempo-trabajado">--:--:--</span></p>
    </div>
</div>
</div>

<!-- ID oculto del usuario -->
<input type="hidden" id="user_id" value="<?= $_SESSION['user']['id'] ?>">

<!-- Registro Horario JS -->
<script type="module" src="/public/js/registro_horario_cronometro.js"></script>
<script type="module" src="/public/js/registro_horario_ui.js"></script>
<script type="module" src="/public/js/registro_horario_api.js"></script>
<script type="module" src="/public/js/registro_horario.js"></script>
