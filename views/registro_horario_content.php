<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="card p-5 shadow text-center container-fluid d-flex justify-content-center align-items-center" style="max-width: 450px; width: 100%;">
    <h2 class="mb-4">Control de Horas</h2>

    <!-- Reloj actual -->
    <div id="reloj" class="display-4 mb-3">00:00:00</div>

    <!-- Cronómetro de jornada -->
    <h6 id="estado-cronometro" class="text-muted">Esperando inicio...</h6>
    <div id="cronometro" class="mb-4 text-primary fw-bold">00:00:00</div>

    <!-- Botones de acción -->
    <div class="d-grid gap-2 mb-4">
        <button id="btn-iniciar-jornada" class="btn" style="background-color: var(--color-success); color: white;">
            <i class="bi bi-play-fill"></i> Iniciar jornada
        </button>
        <button id="btn-iniciar-descanso" class="btn d-none" style="background-color: var(--color-warning); color: black;">
            <i class="bi bi-cup-hot-fill"></i> Iniciar descanso
        </button>
        <button id="btn-finalizar-descanso" class="btn d-none" style="background-color: var(--color-primary); color: white;">
            <i class="bi bi-arrow-return-left"></i> Finalizar descanso
        </button>
        <button id="btn-finalizar-jornada" class="btn d-none" style="background-color: var(--color-danger); color: white;">
            <i class="bi bi-stop-fill"></i> Finalizar jornada
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

<!-- ID oculto del usuario -->
<input type="hidden" id="user_id" value="<?= $_SESSION['user']['id'] ?>">

<!-- Registro Horario JS -->
<script type="module" src="/public/js/registro_horario_cronometro.js"></script>
<script type="module" src="/public/js/registro_horario_ui.js"></script>
<script type="module" src="/public/js/registro_horario_api.js"></script>
<script type="module" src="/public/js/registro_horario.js"></script>
