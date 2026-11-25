<?php

namespace App\Http\Controllers;

use App\Models\Edicao;
use App\Models\Inscricao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * CONTROLLER - Inscrição do participante na edição (EQUIPE 2).
 * A inscrição nasce "pendente"; a confirmação é feita pela Equipe 3 após o Pix.
 */
class InscricaoController extends Controller
{
    // MINHAS INSCRIÇÕES (histórico de todas as edições)
    public function index()
    {
        $inscricoes = Inscricao::with('edicao')
            ->withCount('atividades')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('inscricoes.index', compact('inscricoes'));
    }

    // TELA DE CONFIRMAÇÃO DA INSCRIÇÃO
    public function create()
    {
        $edicao = Edicao::vigente();

        if (! $edicao) {
            return redirect()->route('home')->with('erro', 'Não há edição com inscrições em andamento.');
        }

        $existente = $this->inscricaoExistente($edicao);
        if ($existente) {
            return redirect()->route('inscricoes.show', $existente)
                ->with('erro', 'Você já está inscrito nesta edição.');
        }

        $motivo = $edicao->motivoInscricaoFechada(Auth::user()->tipo_participante);

        return view('inscricoes.create', compact('edicao', 'motivo'));
    }

    // SALVAR
    public function store(Request $request)
    {
        $edicao = Edicao::vigente();

        if (! $edicao) {
            return redirect()->route('home')->with('erro', 'Não há edição com inscrições em andamento.');
        }

        $existente = $this->inscricaoExistente($edicao);
        if ($existente) {
            return redirect()->route('inscricoes.show', $existente)
                ->with('erro', 'Você já está inscrito nesta edição.');
        }

        $motivo = $edicao->motivoInscricaoFechada(Auth::user()->tipo_participante);
        if ($motivo) {
            return redirect()->route('inscricoes.create')->with('erro', $motivo);
        }

        $inscricao = Inscricao::create([
            'user_id'   => Auth::id(),
            'edicao_id' => $edicao->id,
            'status'    => Inscricao::PENDENTE,
        ]);

        return redirect()->route('inscricoes.atividades.edit', $inscricao)
            ->with('sucesso', 'Inscrição criada! Agora escolha as atividades que deseja assistir.');
    }

    // DETALHES / SITUAÇÃO DA INSCRIÇÃO
    public function show(Inscricao $inscricao)
    {
        Gate::authorize('view', $inscricao);

        $inscricao->load(['edicao', 'atividades.local']);
        $atividades = $inscricao->atividades->sortBy('inicio');

        return view('inscricoes.show', compact('inscricao', 'atividades'));
    }

    private function inscricaoExistente(Edicao $edicao): ?Inscricao
    {
        return Inscricao::where('user_id', Auth::id())
            ->where('edicao_id', $edicao->id)
            ->first();
    }
}
