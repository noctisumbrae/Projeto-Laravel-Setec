<?php

namespace App\Http\Controllers;

use App\Http\Requests\InscricaoRequest;
use App\Models\Inscricao;
use App\Models\Atividade;
use App\Models\Edicao;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class InscricaoController extends Controller
{
    public function index(): View
    {
        $inscricoes = Inscricao::with('user', 'edicao', 'atividades')
            ->orderBy('created_at')
            ->paginate(10);

        return view('inscricoes.index', compact('inscricoes'));
    }

    public function create(): View
    {
        $edicoes = Edicao::orderByDesc('ano')->get();
        $users = User::orderBy('name')->get();

        return view('inscricoes.create', compact('edicoes', 'users'));
    }

    public function store(InscricaoRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Verificar se já existe inscrição para este usuário nesta edição
        $existing = Inscricao::where('user_id', $validated['user_id'])
            ->where('edicao_id', $validated['edicao_id'])
            ->first();

        if ($existing) {
            return redirect()->route('inscricoes.create')
                ->with('error', 'Usuário já possui inscrição nesta edição.');
        }

        $inscricao = Inscricao::create([
            'user_id' => $validated['user_id'],
            'edicao_id' => $validated['edicao_id'],
            'status' => 'pendente',
        ]);

        return redirect()->route('inscricoes.index')
            ->with('success', 'Inscrição criada com status pendente.');
    }

    public function show(Inscricao $inscricao): View
    {
        $inscricao->load('user', 'edicao', 'atividades');

        return view('inscricoes.show', compact('inscricao'));
    }

    public function edit(Inscricao $inscricao): View
    {
        $edicoes = Edicao::orderByDesc('ano')->get();
        $users = User::orderBy('name')->get();

        return view('inscricoes.edit', compact('inscricao', 'edicoes', 'users'));
    }

    public function update(InscricaoRequest $request, Inscricao $inscricao): RedirectResponse
    {
        $validated = $request->validated();

        // Só permite atualizar se a inscrição estiver pendente
        if (!$inscricao->editavel()) {
            return redirect()->route('inscricoes.show', $inscricao)
                ->with('error', 'Inscrição não pode ser alterada. Status atual: ' . $inscricao->status);
        }

        $inscricao->update($validated);

        return redirect()->route('inscricoes.index')
            ->with('success', 'Inscrição atualizada com sucesso.');
    }

    public function destroy(Inscricao $inscricao): RedirectResponse
    {
        // Remover também as atividades associadas
        $inscricao->atividades()->delete();
        $inscricao->delete();

        return redirect()->route('inscricoes.index')
            ->with('success', 'Inscrição removida com sucesso.');
    }

    // Ações específicas da Equipe 3

    public function confirmar(Inscricao $inscricao): RedirectResponse
    {
        Gate::authorize('confirmar', $inscricao);

        $inscricao->update(['status' => Inscricao::CONFIRMADA]);

        return redirect()->route('inscricoes.index')
            ->with('success', 'Inscrição confirmada com sucesso.');
    }

    public function cancelar(Inscricao $inscricao): RedirectResponse
    {
        Gate::authorize('cancelar', $inscricao);

        $inscricao->update(['status' => Inscricao::CANCELADA]);

        return redirect()->route('inscricoes.index')
            ->with('success', 'Inscrição cancelada com sucesso.');
    }

    public function selecionarAtividade(InscricaoRequest $request, Inscricao $inscricao): RedirectResponse
    {
        Gate::authorize('selecionarAtividade', $inscricao);

        $validated = $request->validated();
        $atividadeId = $validated['atividade_id'] ?? null;

        if (!$atividadeId) {
            return redirect()->route('inscricoes.show', $inscricao)
                ->with('error', 'Selecione uma atividade.');
        }

        $atividade = Atividade::find($atividadeId);

        if (!$atividade) {
            return redirect()->route('inscricoes.show', $inscricao)
                ->with('error', 'Atividade não encontrada.');
        }

        // Verificar se a atividade pertence à mesma edição da inscrição
        if ($atividade->edicao_id !== $inscricao->edicao_id) {
            return redirect()->route('inscricoes.show', $inscricao)
                ->with('error', 'A atividade não pertence à mesma edição.');
        }

        // Verificar capacidade
        $totalConfirmados = $inscricao->atividades()
            ->wherePivot('status', Inscricao::ATIVIDADE_CONFIRMADA)
            ->count();

        if ($atividade->capacidade && $totalConfirmados >= $atividade->capacidade) {
            return redirect()->route('inscricoes.show', $inscricao)
                ->with('error', 'Atividade lotada (capacidade máxima atingida).');
        }

        // Inserir ou atualizar a atividade na inscrição
        $existing = $inscricao->atividades()->where('atividade_id', $atividadeId)->first();

        if ($existing) {
            $existing->update(['pivot' => ['status' => Inscricao::ATIVIDADE_CONFIRMADA]]);
        } else {
            $inscricao->atividades()->attach($atividadeId, ['status' => Inscricao::ATIVIDADE_CONFIRMADA]);
        }

        return redirect()->route('inscricoes.show', $inscricao)
            ->with('success', 'Atividade selecionada e confirmada com sucesso.');
    }

    public function confirmarAtividade(Inscricao $inscricao, $atividadeId): RedirectResponse
    {
        Gate::authorize('confirmarAtividade', [$inscricao, $atividadeId]);

        $inscricao->atividades()
            ->where('atividade_id', $atividadeId)
            ->update(['pivot' => ['status' => Inscricao::ATIVIDADE_CONFIRMADA]]);

        return redirect()->route('inscricoes.show', $inscricao)
            ->with('success', 'Atividade confirmada com sucesso.');
    }

    public function deselecionarAtividade(Inscricao $inscricao, $atividadeId): RedirectResponse
    {
        Gate::authorize('selecionarAtividade', $inscricao);

        $inscricao->atividades()
            ->where('atividade_id', $atividadeId)
            ->delete();

        return redirect()->route('inscricoes.show', $inscricao)
            ->with('success', 'Atividade removida da inscrição.');
    }
}