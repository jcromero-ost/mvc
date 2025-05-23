<?php if (!isset($calendario['anio'])): ?>
  <div class="alert alert-warning">Año no definido para generar la vista anual.</div>
<?php else: ?>
  <?php
    $anio = $calendario['anio'];
    $calAnual = CalendarioLaboral::generarCalendarioAnual($anio);

    $nombresMes = [
      1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
      5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
      9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
  ?>

  <div class="cal-anual-container">
    <?php foreach ($calAnual as $mes => $cal): ?>
      <div class="cal-mes">
        <div class="nombre-mes"><?= $nombresMes[$mes] ?></div>
        <table class="table table-sm table-bordered text-center mb-0">
          <thead>
            <tr>
              <?php foreach (['D', 'L', 'M', 'X', 'J', 'V', 'S'] as $dia): ?>
                <th><?= $dia ?></th>
              <?php endforeach; ?>
            </tr>
          </thead>
          <tbody>
            <tr>
              <?php
                $col = 0;
                for ($i = 0; $i < $cal['primer_dia_semana']; $i++) {
                  echo '<td></td>';
                  $col++;
                }

                foreach ($cal['dias'] as $dia) {
                  $clase = $dia['es_fin_de_semana'] ? 'table-secondary' : '';
                  echo "<td class='$clase'>{$dia['numero']}</td>";
                  $col++;
                  if ($col % 7 === 0) echo '</tr><tr>';
                }

                while ($col % 7 !== 0) {
                  echo '<td></td>';
                  $col++;
                }
              ?>
            </tr>
          </tbody>
        </table>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
