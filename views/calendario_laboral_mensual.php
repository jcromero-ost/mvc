<?php if (!isset($calendario) || empty($calendario['dias'])): ?>
  <div class="alert alert-info">Selecciona un año y mes para visualizar el calendario laboral.</div>
<?php else: ?>
  <div class="cal-mensual-container">
    <table class="table table-bordered text-center align-middle mb-0" style="height: 100%; table-layout: fixed;">
      <thead class="table-light">
        <tr>
          <?php foreach (['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $dia): ?>
            <th class="small p-1"><?= $dia ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php
        $primerDia = $calendario['primer_dia_semana'];
        $dias = $calendario['dias'];
        $totalCeldas = $primerDia + count($dias);
        $filas = ceil($totalCeldas / 7);
        $i = 0;

        for ($fila = 0; $fila < $filas; $fila++): ?>
          <tr style="height: calc((100vh - 260px) / <?= $filas ?>);">
            <?php for ($col = 0; $col < 7; $col++): ?>
              <?php
              if ($i < $primerDia) {
                echo "<td></td>";
              } else {
                $index = $i - $primerDia;
                if (isset($dias[$index])) {
                  $dia = $dias[$index];
                  $clase = $dia['es_fin_de_semana'] ? 'table-secondary' : '';
                  echo "<td class='align-top p-1 $clase'><div class='fw-bold small mb-1'>{$dia['numero']}</div><div class='contenido-dia'></div></td>";
                } else {
                  echo "<td></td>";
                }
              }
              $i++;
              ?>
            <?php endfor; ?>
          </tr>
        <?php endfor; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
