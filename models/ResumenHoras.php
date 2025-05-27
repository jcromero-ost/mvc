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

        // Obtener horas diarias activas del contrato del usuario (último contrato activo)
        $stmt = $conn->prepare("
            SELECT horas_diarias, fecha_inicio
            FROM contratos_laborales
            WHERE usuario_id = :usuario_id AND activo = 1
            ORDER BY fecha_inicio DESC
            LIMIT 1
        ");
        $stmt->execute(['usuario_id' => $usuarioId]);
        $contrato = $stmt->fetch(PDO::FETCH_ASSOC);
        $horasDiarias = $contrato ? floatval($contrato['horas_diarias']) : 8.0;

        // Obtener eventos ordenados
        $sql = "
            SELECT fecha_hora, tipo_evento
            FROM registro_horarios
            WHERE user_id = :usuario_id AND YEAR(fecha_hora) = :anio
            ORDER BY fecha_hora ASC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['usuario_id' => $usuarioId, 'anio' => $anio]);
        $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agrupar eventos por día
        $dias = [];
        foreach ($eventos as $evento) {
            $dia = (new DateTime($evento['fecha_hora']))->format('Y-m-d');
            $dias[$dia][] = $evento;
        }

        // Acumular segundos trabajados por mes
        foreach ($dias as $dia => $eventosDia) {
            $totalSegundosDia = 0;
            $trabajando = false;
            $descansando = false;
            $inicioJornada = null;
            $inicioDescanso = null;
            $segundosDescanso = 0;

            foreach ($eventosDia as $evento) {
                switch ($evento['tipo_evento']) {
                    case 'inicio_jornada':
                        $inicioJornada = new DateTime($evento['fecha_hora']);
                        $trabajando = true;
                        $descansando = false;
                        $inicioDescanso = null;
                        $segundosDescanso = 0;
                        break;

                    case 'inicio_descanso':
                        if ($trabajando && !$descansando) {
                            $inicioDescanso = new DateTime($evento['fecha_hora']);
                            $descansando = true;
                        }
                        break;

                    case 'fin_descanso':
                        if ($trabajando && $descansando && $inicioDescanso) {
                            $finDescanso = new DateTime($evento['fecha_hora']);
                            $segundosDescanso += $finDescanso->getTimestamp() - $inicioDescanso->getTimestamp();
                            $descansando = false;
                            $inicioDescanso = null;
                        }
                        break;

                    case 'fin_jornada':
                        if ($trabajando && $inicioJornada) {
                            $finJornada = new DateTime($evento['fecha_hora']);
                            $duracion = $finJornada->getTimestamp() - $inicioJornada->getTimestamp() - $segundosDescanso;
                            $totalSegundosDia += max(0, $duracion);

                            $trabajando = false;
                            $descansando = false;
                            $inicioJornada = null;
                            $inicioDescanso = null;
                            $segundosDescanso = 0;
                        }
                        break;
                }
            }

            $mes = intval((new DateTime($dia))->format('m'));
            $resumen[$mes]['trabajadas'] = self::sumarTiempos(
                $resumen[$mes]['trabajadas'],
                self::segundosAFormatoHora($totalSegundosDia)
            );
        }

        // Antes del foreach que recorre los días, consulta los festivos del mes
        function obtenerFestivosDelMes($anio, $mes, $conn) {
            $inicioMes = "$anio-$mes-01";
            $finMes = date("Y-m-t", strtotime($inicioMes)); // Último día del mes

            $sql = "SELECT fecha FROM festivos WHERE activo = 1 AND fecha BETWEEN ? AND ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$inicioMes, $finMes]);

            $festivos = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $festivos[] = $row['fecha'];
            }
            return $festivos;
        }

        // Ahora por cada mes calculamos el límite según días del mes * horas_diarias y separamos horas extras
        foreach ($resumen as $mes => $tiempos) {
            $festivos = obtenerFestivosDelMes($anio, $mes, $conn);
            // Calcular días del mes considerando fecha de inicio del contrato
            $diasDelMes = 0;
            $fechaInicioContrato = $contrato ? new DateTime($contrato['fecha_inicio']) : new DateTime("$anio-$mes-01");
            $fechaInicioMes = new DateTime("$anio-$mes-01");
            // El día inicial para contar es el máximo entre inicio contrato y primer día mes
            $fechaInicio = max($fechaInicioContrato, $fechaInicioMes);
            $fechaFin = new DateTime("$anio-$mes-" . cal_days_in_month(CAL_GREGORIAN, $mes, $anio));

            while ($fechaInicio <= $fechaFin) {
                $diaSemana = $fechaInicio->format('N'); // 1 (lunes) a 7 (domingo)
                $fechaStr = $fechaInicio->format('Y-m-d');

                // Contar solo lunes a viernes y que no sea festivo
                if ($diaSemana < 6 && !in_array($fechaStr, $festivos)) {
                    $diasDelMes++;
                }
                $fechaInicio->modify('+1 day');
            }

            // Calcular límite mensual en segundos
            $limiteMensualSegundos = $horasDiarias * $diasDelMes * 3600;

            $fechaActual = new DateTime();
            $mesActual = intval($fechaActual->format('m'));
            $anioActual = intval($fechaActual->format('Y'));

            $totalSegundos = self::formatoHoraASegundos($tiempos['trabajadas']);
            $extras = $totalSegundos - $limiteMensualSegundos;
            $trabajadas = min($totalSegundos, $limiteMensualSegundos);

            // Solo mostrar extras negativas si el mes y año ya pasaron o es el mes actual
            if (($anio < $anioActual) || ($anio == $anioActual && $mes <= $mesActual)) {
                $extrasFormateado = ($extras < 0 ? '-' : '') . self::segundosAFormatoHora(abs($extras));
                $trabajadasFormateado = self::segundosAFormatoHora($trabajadas);
            } else {
                // Mes futuro: no mostrar extras negativas (faltantes) ni horas trabajadas
                $extrasFormateado = $extras < 0 ? '00:00:00' : self::segundosAFormatoHora($extras);
                $trabajadasFormateado = '00:00:00';
            }

            //echo "Mes: $mes, Límite mensual: " . self::segundosAFormatoHora($limiteMensualSegundos) . "<br>";

            $resumen[$mes]['trabajadas'] = $trabajadasFormateado;
            $resumen[$mes]['extras'] = $extrasFormateado;
        }


        return $resumen;
    }

    // Suma dos tiempos en formato HH:MM:SS y devuelve en el mismo formato
    private static function sumarTiempos($hora1, $hora2)
    {
        $segundos1 = self::formatoHoraASegundos($hora1);
        $segundos2 = self::formatoHoraASegundos($hora2);
        $suma = $segundos1 + $segundos2;
        return self::segundosAFormatoHora($suma);
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