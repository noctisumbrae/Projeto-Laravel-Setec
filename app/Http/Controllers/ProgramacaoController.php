<?php

namespace App\Http\Controllers;

use App\Models\Atividade;
use App\Models\Edicao;
use Illuminate\Http\Request;

/** CONTROLLER - Consulta pública da programação da edição vigente. */
class ProgramacaoController extends Controller
{
    public function index(Request $request)
    {
        $filtro = $request->validate([
            'tipo' => ['nullable', 'in:' . Atividade::TIPO_PALESTRA . ',' . Atividade::TIPO_MINICURSO],
        ]);

        $edicao = Edicao::vigente();
        $porDia = collect();

        if ($edicao) {
            $porDia = Atividade::comOcupacao()
                ->with('local')
                ->where('edicao_id', $edicao->id)
                ->when($filtro['tipo'] ?? null, fn ($q, $tipo) => $q->where('tipo', $tipo))
                ->orderBy('inicio')
                ->get()
                ->groupBy(fn (Atividade $a) => $a->inicio->format('Y-m-d'));
        }

        return view('programacao.index', [
            'edicao' => $edicao,
            'porDia' => $porDia,
            'tipo'   => $filtro['tipo'] ?? null,
        ]);
    }

    public function show(Atividade $atividade)
    {
        $atividade = Atividade::comOcupacao()->with(['local', 'edicao'])->findOrFail($atividade->id);

        return view('programacao.show', compact('atividade'));
    }
}
