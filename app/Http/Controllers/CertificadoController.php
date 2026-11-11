<?php

namespace App\Http\Controllers;

class CertificadoController extends Controller
{
    public function index()
    {
        // Dados fictícios para testar o cálculo
        $atividades = [
            [
                'titulo' => 'Palestra de abertura',
                'duracao_minutos' => 60,
                'presenca_confirmada' => true
            ],
            [
                'titulo' => 'Minicurso de Laravel',
                'duracao_minutos' => 120,
                'presenca_confirmada' => true
            ],
            [
                'titulo' => 'Oficina de Redes',
                'duracao_minutos' => 90,
                'presenca_confirmada' => false
            ]
        ];

        // Soma apenas atividades com presença confirmada
        $totalMinutos = collect($atividades)
            ->filter(fn ($atividade) => $atividade['presenca_confirmada'])
            ->sum('duracao_minutos');

        return view('certificados.index', compact(
            'atividades',
            'totalMinutos'
        ));
    }
}
