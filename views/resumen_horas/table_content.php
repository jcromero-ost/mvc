<?php
$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];

$totalHorasDadas = 0;
$totalHoras = 120;
$totalExtras = 0;

function tiempoASegundos($tiempo) {
    list($h, $m, $s) = explode(':', $tiempo);
    return $h * 3600 + $m * 60 + $s;
}

function segundosATiempo($seg) {
    $horas = floor($seg / 3600);
    $minutos = floor(($seg % 3600) / 60);
    $segundos = $seg % 60;
    return sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
}

function formatoHorasYMinutos($segundos) {
    $horas = floor($segundos / 3600);
    $minutos = floor(($segundos % 3600) / 60);
    return "{$horas} horas y {$minutos} minutos";
}
?>

<div class="table-responsive">
    <table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>Mes</th>
                <th>Horas Trabajadas</th>
                <th>Horas Extras</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resumen as $mes => $datos): 
                $segTrab = tiempoASegundos($datos['trabajadas']);
                $segExt  = tiempoASegundos($datos['extras']);
                $totalHorasDadas += $segTrab;
                $totalExtras += $segExt;
            ?>
                <tr>
                    <td><?= $meses[$mes] ?></td>
                    <td><?= $datos['trabajadas'] ?></td>
                    <td class="<?=
                        strpos($datos['extras'], '-') === 0 ? 'text-danger' : 
                        (!empty($datos['extras']) && $datos['extras'] !== '00:00:00' ? 'text-success' : '')
                    ?>">
                        <?= 
                            strpos($datos['extras'], '-') === 0 
                            ? $datos['extras'] 
                            : ($datos['extras'] !== '00:00:00' ? '+' . $datos['extras'] : $datos['extras']) 
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="fw-bold table-light">
            <tr>
                <td>Total</td>
                <td><?= formatoHorasYMinutos($totalHorasDadas) ?></td>
                <td>
                    <?php if ($totalExtras < 0): ?>
                        <span style="color: red;">
                            Debes un total de <?= formatoHorasYMinutos(abs($totalExtras)) ?>
                        </span>
                    <?php elseif ($totalExtras > 0): ?>
                        <span style="color: green;">
                            Tienes un total de <?= formatoHorasYMinutos(abs($totalExtras)) ?>
                        </span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>Total (Trabajadas + Extras)</td>
                <td colspan="2">
                    <?php 
                        $totalCombinado = $totalHorasDadas + $totalExtras;
                        echo formatoHorasYMinutos($totalCombinado);
                    ?>
                </td>
            </tr>
            <tr>
                <td>Vacaciones</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</div>
