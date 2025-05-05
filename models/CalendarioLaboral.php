<?php

class CalendarioLaboral
{
    public static function generarCalendario($anio, $mes)
    {
        $dias = [];

        $totalDias = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

        for ($dia = 1; $dia <= $totalDias; $dia++) {
            $fecha = sprintf('%04d-%02d-%02d', $anio, $mes, $dia);
            $diaSemana = date('w', strtotime($fecha)); // 0 = domingo, 6 = sábado
            $esFinDeSemana = ($diaSemana === 0 || $diaSemana === 6);

            $dias[] = [
                'numero' => $dia,
                'fecha' => $fecha,
                'dia_semana' => $diaSemana,
                'es_fin_de_semana' => $esFinDeSemana,
                'es_festivo' => false, // se podrá marcar luego desde DB
            ];
        }

        return [
            'anio' => $anio,
            'mes' => $mes,
            'primer_dia_semana' => date('w', strtotime("$anio-$mes-01")),
            'total_dias' => $totalDias,
            'dias' => $dias
        ];
    }

    public static function generarCalendarioAnual($anio)
    {
        $calendario = [];
        for ($mes = 1; $mes <= 12; $mes++) {
            $calendario[$mes] = self::generarCalendario($anio, $mes);
        }
        return $calendario;
    }

    public static function generarSemana($anio, $semana)
    {
        $inicio = new DateTime();
        $inicio->setISODate($anio, $semana);

        $semanaDias = [];

        for ($i = 0; $i < 7; $i++) {
            $fecha = (clone $inicio)->modify("+$i day");
            $diaSemana = (int)$fecha->format('w');
            $esFinDeSemana = ($diaSemana === 0 || $diaSemana === 6);

            $semanaDias[] = [
                'fecha' => $fecha->format('Y-m-d'),
                'numero' => $fecha->format('d'),
                'dia_semana' => $diaSemana,
                'es_fin_de_semana' => $esFinDeSemana,
                'es_festivo' => false
            ];
        }

        return $semanaDias;
    }
}
