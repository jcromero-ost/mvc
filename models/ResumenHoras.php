<?php

require_once __DIR__ . '/Database.php';

class ResumenHoras
{
    public static function obtenerResumenMensualPorUsuario($usuarioId, $anio)
{
    $conn = Database::connect();

    $resumen = array_fill(1, 12, [
        'trabajadas' => '00:00:00',
        'extras' => '00:00:00'
    ]);

    // Obtener jornada laboral diaria del contrato
    $stmt = $conn->prepare("
        SELECT horas_diarias
        FROM contratos_laborales
        WHERE usuario_id = :usuario_id AND activo = 1
        ORDER BY fecha_inicio DESC
        LIMIT 1
    ");
    $stmt->execute(['usuario_id' => $usuarioId]);
    $contrato = $stmt->fetch(PDO::FETCH_ASSOC);
    $horasDiarias = $contrato ? floatval($contrato['horas_diarias']) : 8.0;
    $limiteDiarioSegundos = intval($horasDiarias * 3600);

    // Obtener todas las jornadas agrupadas por día
    $sql = "
        SELECT 
            DATE(fecha_hora) as dia,
            MONTH(fecha_hora) as mes,
            MIN(CASE WHEN tipo_evento = 'inicio_jornada' THEN fecha_hora END) as inicio,
            MAX(CASE WHEN tipo_evento = 'fin_jornada' THEN fecha_hora END) as fin,
            MIN(CASE WHEN tipo_evento = 'inicio_descanso' THEN fecha_hora END) as inicio_descanso,
            MAX(CASE WHEN tipo_evento = 'fin_descanso' THEN fecha_hora END) as fin_descanso
        FROM registro_horarios
        WHERE user_id = :usuario_id AND YEAR(fecha_hora) = :anio
        GROUP BY dia
        HAVING inicio IS NOT NULL AND fin IS NOT NULL
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute(['usuario_id' => $usuarioId, 'anio' => $anio]);

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $jornada) {
        $inicio = new DateTime($jornada['inicio']);
        $fin = new DateTime($jornada['fin']);
        $totalSegundos = $fin->getTimestamp() - $inicio->getTimestamp();

        // Restar descanso si aplica
        if ($jornada['inicio_descanso'] && $jornada['fin_descanso']) {
            $inicioDescanso = new DateTime($jornada['inicio_descanso']);
            $finDescanso = new DateTime($jornada['fin_descanso']);
            $totalSegundos -= ($finDescanso->getTimestamp() - $inicioDescanso->getTimestamp());
        }

        $totalSegundos = max($totalSegundos, 0);

        // Separar entre trabajadas normales y extras
        $trabajadas = min($totalSegundos, $limiteDiarioSegundos);
        $extras = max($totalSegundos - $limiteDiarioSegundos, 0);

        $mes = intval($jornada['mes']);
        $resumen[$mes] = self::sumarSegundos($resumen[$mes], $trabajadas, $extras);
    }

    return $resumen;
}

private static function sumarSegundos($acumulado, $trabajadas, $extras)
{
    $trabajadasTot = self::formatoHoraASegundos($acumulado['trabajadas']) + $trabajadas;
    $extrasTot = self::formatoHoraASegundos($acumulado['extras']) + $extras;

    return [
        'trabajadas' => self::segundosAFormatoHora($trabajadasTot),
        'extras' => self::segundosAFormatoHora($extrasTot)
    ];
}

private static function segundosAFormatoHora($segundos)
{
    $horas = floor($segundos / 3600);
    $minutos = floor(($segundos % 3600) / 60);
    $segundos = $segundos % 60;

    return sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
}

private static function formatoHoraASegundos($hora)
{
    list($h, $m, $s) = explode(':', $hora);
    return $h * 3600 + $m * 60 + $s;
}



}
