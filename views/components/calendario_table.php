<?php
// Validar entrada
$anio = isset($_GET['anio']) ? intval($_GET['anio']) : date('Y');
$mes = isset($_GET['mes']) ? intval($_GET['mes']) : date('n');

// Obtener primer día del mes y cuántos días tiene
$primerDiaMes = date('w', strtotime("$anio-$mes-01")); // 0 = domingo
$totalDiasMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

// Arreglo de nombres de días
$diasSemana = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
?>

<div class="table-responsive">
  <table class="table table-bordered text-center align-middle">
    <thead class="table-light">
      <tr>
        <?php foreach ($diasSemana as $dia): ?>
          <th><?= $dia ?></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <?php
      $dia = 1;
      $col = 0;

      echo '<tr>';

      // Espacios en blanco al inicio
      for ($i = 0; $i < $primerDiaMes; $i++) {
        echo '<td></td>';
        $col++;
      }

      while ($dia <= $totalDiasMes) {
        $fecha = "$anio-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-" . str_pad($dia, 2, '0', STR_PAD_LEFT);
        $diaSemana = date('w', strtotime($fecha)); // 0 = domingo

        // Asignar clase para fines de semana
        $esFinDeSemana = ($diaSemana == 0 || $diaSemana == 6);
        $clase = $esFinDeSemana ? 'table-secondary' : 'table-success';

        echo "<td class='$clase'>$dia</td>";

        $dia++;
        $col++;

        // Si termina la fila
        if ($col % 7 == 0) {
          echo '</tr>';
          if ($dia <= $totalDiasMes) echo '<tr>';
        }
      }

      // Completar celdas vacías al final
      while ($col % 7 != 0) {
        echo '<td></td>';
        $col++;
      }

      echo '</tr>';
      ?>
    </tbody>
  </table>
</div>
