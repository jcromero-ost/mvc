<?php
$meses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];

$totalHoras = 0;
$totalExtras = 0;

function tiempoASegundos($tiempo) {
    list($h, $m, $s) = explode(':', $tiempo);
    return $h * 3600 + $m * 60 + $s;
}

function segundosATiempo($seg) {
    return gmdate("H:i:s", $seg);
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
                $totalHoras += $segTrab;
                $totalExtras += $segExt;
            ?>
                <tr>
                    <td><?= $meses[$mes] ?></td>
                    <td><?= $datos['trabajadas'] ?></td>
                    <td><?= $datos['extras'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot class="fw-bold table-light">
            <tr>
                <td>Total</td>
                <td><?= segundosATiempo($totalHoras) ?></td>
                <td><?= segundosATiempo($totalExtras) ?></td>
            </tr>
        </tfoot>
    </table>
</div>
