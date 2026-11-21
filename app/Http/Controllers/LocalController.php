<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocalRequest;
use App\Models\Local;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LocalController extends Controller
{
    public function index(): View
    {
        $locais = Local::orderBy('nome')->paginate(10);

        return view('locais.index', compact('locais'));
    }

    public function create(): View
    {
        return view('locais.create');
    }

    public function store(LocalRequest $request): RedirectResponse
    {
        Local::create($request->validated());

        return redirect()
            ->route('locais.index')
            ->with('success', 'Local cadastrado com sucesso!');
    }

    public function show(Local $local): View
    {
        return view('locais.show', compact('local'));
    }

    public function edit(Local $local): View
    {
        return view('locais.edit', compact('local'));
    }

    public function update(
        LocalRequest $request,
        Local $local
    ): RedirectResponse {
        $local->update($request->validated());

        return redirect()
            ->route('locais.index')
            ->with('success', 'Local atualizado com sucesso!');
    }

    public function destroy(Local $local): RedirectResponse
    {
        $local->delete();

        return redirect()
            ->route('locais.index')
            ->with('success', 'Local excluído com sucesso!');
    }
}