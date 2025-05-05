<?php
$anio = isset($_GET['anio']) ? (int)$_GET['anio'] : (int)date('Y');
$semana = isset($_GET['semana']) ? (int)$_GET['semana'] : (int)date('W');

// Obtener el lunes de la semana ISO especificada
$inicioSemana = new DateTime();
$inicioSemana->setISODate($anio, $semana);

// Generar los 7 días (lunes a domingo)
$semanaDias = [];
for ($i = 0; $i < 7; $i++) {
    $fecha = (clone $inicioSemana)->modify("+$i day");
    $semanaDias[] = [
        'fecha' => $fecha->format('Y-m-d'),
        'numero' => $fecha->format('d'),
        'mes' => $fecha->format('n'),
        'dia_nombre' => $fecha->format('l'),
        'es_hoy' => ($fecha->format('Y-m-d') === date('Y-m-d'))
    ];
}

// Traducción de días al español
$diasTraducidos = [
    'Monday' => 'Lunes',
    'Tuesday' => 'Martes',
    'Wednesday' => 'Miércoles',
    'Thursday' => 'Jueves',
    'Friday' => 'Viernes',
    'Saturday' => 'Sábado',
    'Sunday' => 'Domingo'
];
?>

<div class="cal-semanal-container">
  <table class="table table-bordered text-center align-middle mb-0">
    <thead class="table-light">
      <tr>
        <?php foreach ($semanaDias as $dia): ?>
          <th>
            <?= $diasTraducidos[$dia['dia_nombre']] ?><br>
            <small><?= $dia['numero'] ?>/<?= $dia['mes'] ?></small>
          </th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <tr>
        <?php foreach ($semanaDias as $dia): ?>
          <td class="<?= $dia['es_hoy'] ? 'cal-dia-hoy' : '' ?>">
            <div class="fw-bold mb-1"><?= $dia['numero'] ?></div>
            <div class="contenido-dia">Sin datos</div>
          </td>
        <?php endforeach; ?>
      </tr>
    </tbody>
  </table>
</div>
