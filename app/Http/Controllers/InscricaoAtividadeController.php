<?php

namespace App\Http\Controllers;

use App\Models\Atividade;
use App\Models\Inscricao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

/**
 * CONTROLLER - Seleção de palestras e minicursos de uma inscrição (EQUIPE 2).
 *
 * Regras aplicadas em update():
 *  1. selecionar uma atividade NÃO reserva vaga (a vaga só é confirmada pela Equipe 3);
 *  2. não é possível escolher atividade sem vagas disponíveis;
 *  3. respeitar os limites de palestras/minicursos da edição;
 *  4. não permitir atividades com horários sobrepostos;
 *  5. atividades já confirmadas pela Equipe 3 não podem ser removidas.
 */
class InscricaoAtividadeController extends Controller
{
    // FORMULÁRIO DE SELEÇÃO
    public function edit(Inscricao $inscricao)
    {
        Gate::authorize('view', $inscricao);

        if (! $inscricao->editavel()) {
            return redirect()->route('inscricoes.show', $inscricao)
                ->with('erro', 'Esta inscrição não pode mais ser alterada.');
        }

        $inscricao->load(['edicao', 'atividades']);

        $porDia = Atividade::comOcupacao()
            ->with('local')
            ->where('edicao_id', $inscricao->edicao_id)
            ->orderBy('inicio')
            ->get()
            ->groupBy(fn (Atividade $a) => $a->inicio->format('Y-m-d'));

        return view('inscricoes.atividades', [
            'inscricao'     => $inscricao,
            'edicao'        => $inscricao->edicao,
            'porDia'        => $porDia,
            'selecionadas'  => $inscricao->atividades->pluck('id')->all(),
            'statusAtuais'  => $inscricao->atividades->pluck('pivot.status', 'id')->all(),
        ]);
    }

    // SALVAR SELEÇÃO
    public function update(Request $request, Inscricao $inscricao)
    {
        Gate::authorize('view', $inscricao);

        if (! $inscricao->editavel()) {
            return redirect()->route('inscricoes.show', $inscricao)
                ->with('erro', 'Esta inscrição não pode mais ser alterada.');
        }

        $dados = $request->validate([
            'atividades'   => ['required', 'array', 'min:1'],
            'atividades.*' => ['integer', Rule::exists('atividades', 'id')->where('edicao_id', $inscricao->edicao_id)],
        ], [
            'atividades.required' => 'Escolha pelo menos uma atividade.',
            'atividades.min'      => 'Escolha pelo menos uma atividade.',
        ]);

        $edicao = $inscricao->edicao;

        // Situação atual: [atividade_id => status]
        $statusAtuais = $inscricao->atividades()->pluck('inscricao_atividade.status', 'atividades.id');

        // Atividades já confirmadas pela Equipe 3 continuam na inscrição.
        $confirmadas = $statusAtuais->filter(fn ($s) => $s === Inscricao::ATIVIDADE_CONFIRMADA)->keys();

        $ids = collect($dados['atividades'])->map(fn ($id) => (int) $id)->merge($confirmadas)->unique()->values();

        $atividades = Atividade::comOcupacao()->whereIn('id', $ids)->get();

        $erros = [];

        // Regra 2: vagas (só para atividades novas ou que estavam "lotada")
        foreach ($atividades as $atividade) {
            $jaGarantida = in_array($statusAtuais[$atividade->id] ?? null, [
                Inscricao::ATIVIDADE_SELECIONADA,
                Inscricao::ATIVIDADE_CONFIRMADA,
            ], true);

            if (! $jaGarantida && $atividade->vagas_disponiveis <= 0) {
                $erros[] = "A atividade \"{$atividade->titulo}\" está sem vagas. Escolha outra.";
            }
        }

        // Regra 3: limites da edição (null = sem limite)
        $totalPalestras  = $atividades->where('tipo', Atividade::TIPO_PALESTRA)->count();
        $totalMinicursos = $atividades->where('tipo', Atividade::TIPO_MINICURSO)->count();

        if ($edicao->limite_palestras !== null && $totalPalestras > $edicao->limite_palestras) {
            $erros[] = "Você pode escolher no máximo {$edicao->limite_palestras} palestra(s); selecionou {$totalPalestras}.";
        }
        if ($edicao->limite_minicursos !== null && $totalMinicursos > $edicao->limite_minicursos) {
            $erros[] = "Você pode escolher no máximo {$edicao->limite_minicursos} minicurso(s); selecionou {$totalMinicursos}.";
        }

        // Regra 4: conflito de horário
        foreach (Atividade::encontrarConflitos($atividades) as [$a, $b]) {
            $erros[] = "Conflito de horário entre \"{$a->titulo}\" e \"{$b->titulo}\".";
        }

        if ($erros) {
            return back()->withErrors(['atividades' => $erros])->withInput();
        }

        // Salva mantendo o status de quem já estava selecionado/confirmado.
        $sincronizar = $atividades->mapWithKeys(function (Atividade $atividade) use ($statusAtuais) {
            $atual = $statusAtuais[$atividade->id] ?? null;
            $status = in_array($atual, [Inscricao::ATIVIDADE_SELECIONADA, Inscricao::ATIVIDADE_CONFIRMADA], true)
                ? $atual
                : Inscricao::ATIVIDADE_SELECIONADA;

            return [$atividade->id => ['status' => $status]];
        })->all();

        $inscricao->atividades()->sync($sincronizar);

        return redirect()->route('inscricoes.show', $inscricao)
            ->with('sucesso', 'Atividades salvas! A vaga só é garantida após a aprovação do pagamento.');
    }
}
