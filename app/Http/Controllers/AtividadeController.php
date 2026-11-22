<?php

namespace App\Http\Controllers;

use App\Http\Requests\AtividadeRequest;
use App\Models\Atividade;
use App\Models\Local;
use App\Models\Edicao;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AtividadeController extends Controller
{
    public function index(): View
    {
        $atividades = Atividade::with('local')
            ->orderBy('data')
            ->orderBy('horario')
            ->paginate(10);

        return view('atividades.index', compact('atividades'));
    }

    public function create(): View
    {
        $locais = Local::orderBy('nome')->get();
        $edicoes = Edicao::orderByDesc('ano')->get();
        return view('atividades.create', compact('locais', 'edicoes'));
    }

    public function store(AtividadeRequest $request): RedirectResponse
    {
        Atividade::create($request->validated());

        return redirect()
            ->route('atividades.index')
            ->with('success', 'Atividade cadastrada com sucesso!');
    }
    public function show(Atividade $atividade): View
    {
        $atividade->load('local');

        return view('atividades.show', compact('atividade'));
    }

    public function edit(Atividade $atividade): View
    {
        $locais = Local::orderBy('nome')->get();
        $edicoes = Edicao::orderByDesc('ano')->get();

        return view('atividades.edit', compact('atividade', 'locais', 'edicoes'));
    }

    public function update(
        AtividadeRequest $request,
        Atividade $atividade
    ): RedirectResponse {
        $atividade->update($request->validated());

        return redirect()
            ->route('atividades.index')
            ->with('success', 'Atividade atualizada com sucesso!');
    }

    public function destroy(Atividade $atividade): RedirectResponse
    {
        $atividade->delete();

        return redirect()
            ->route('atividades.index')
            ->with('success', 'Atividade excluída com sucesso!');
    }
}
