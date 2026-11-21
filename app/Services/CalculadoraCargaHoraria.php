<?php

namespace App\Services;

class CalculadoraCargaHoraria
{
    public function calcular(array $atividades): int
    {
        $totalMinutos = 0;

        foreach ($atividades as $atividade) {
            if (($atividade['presenca_confirmada'] ?? false) === true) {
                $totalMinutos += (int) ($atividade['duracao_minutos'] ?? 0);
            }
        }

        return $totalMinutos;
    }
}
