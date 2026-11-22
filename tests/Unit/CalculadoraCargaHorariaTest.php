<?php

namespace Tests\Unit;

use App\Services\CalculadoraCargaHoraria;
use PHPUnit\Framework\TestCase;

class CalculadoraCargaHorariaTest extends TestCase
{
    public function test_soma_apenas_atividades_com_presenca(): void
    {
        $atividades = [
            [
                'duracao_minutos' => 60,
                'presenca_confirmada' => true
            ],
            [
                'duracao_minutos' => 120,
                'presenca_confirmada' => true
            ],
            [
                'duracao_minutos' => 90,
                'presenca_confirmada' => false
            ]
        ];

        $calculadora = new CalculadoraCargaHoraria();

        $resultado = $calculadora->calcular($atividades);

        $this->assertEquals(180, $resultado);
    }

    public function test_retorna_zero_sem_presencas(): void
    {
        $atividades = [
            [
                'duracao_minutos' => 60,
                'presenca_confirmada' => false
            ]
        ];

        $calculadora = new CalculadoraCargaHoraria();

        $resultado = $calculadora->calcular($atividades);

        $this->assertEquals(0, $resultado);
    }
}
