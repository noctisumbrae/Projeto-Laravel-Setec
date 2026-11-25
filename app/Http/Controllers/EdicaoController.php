<?php

namespace App\Http\Controllers;

use App\Http\Requests\EdicaoRequest;
use App\Models\Edicao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EdicaoController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $edicoes = Edicao::query()
            ->when(in_array($status, Edicao::STATUS, true), fn ($q) => $q->where('status', $status))
            ->orderByDesc('ano')
            ->paginate(10)
            ->withQueryString();

        return view('edicoes.index', compact('edicoes', 'status'));
    }

    public function create(): View
    {
        return view('edicoes.create');
    }

    public function store(EdicaoRequest $request): RedirectResponse
    {
        $edicao = Edicao::create($request->validated() + ['status' => Edicao::PLANEJADA]);

        return redirect()
            ->route('edicoes.show', $edicao)
            ->with('success', 'Edição cadastrada com sucesso.');
    }

    public function show(Edicao $edicao): View
    {
        return view('edicoes.show', compact('edicao'));
    }

    public function edit(Edicao $edicao): View|RedirectResponse
    {
        if ($edicao->estaEncerrada()) {
            return redirect()
                ->route('edicoes.show', $edicao)
                ->with('error', 'Edições encerradas não podem ser editadas.');
        }

        return view('edicoes.edit', compact('edicao'));
    }

    public function update(EdicaoRequest $request, Edicao $edicao): RedirectResponse
    {
        if ($edicao->estaEncerrada()) {
            return redirect()
                ->route('edicoes.show', $edicao)
                ->with('error', 'Edições encerradas não podem ser editadas.');
        }

        $edicao->update($request->validated());

        return redirect()
            ->route('edicoes.show', $edicao)
            ->with('success', 'Edição atualizada com sucesso.');
    }

    public function ativar(Edicao $edicao): RedirectResponse
    {
        $resultado = DB::transaction(function () use ($edicao) {
            // Trava as linhas ativas para evitar duas ativações simultâneas
            $edicao = Edicao::whereKey($edicao->id)->lockForUpdate()->first();

            if ($edicao->estaEncerrada()) {
                return ['error', 'Uma edição encerrada não pode ser reativada.'];
            }

            if ($edicao->estaAtiva()) {
                return ['error', 'Esta edição já está ativa.'];
            }

            $outra = Edicao::ativa()->lockForUpdate()->first();

            if ($outra) {
                return ['error', "Já existe uma edição ativa ({$outra->nome} - {$outra->ano}). Encerre-a antes de ativar outra."];
            }

            $edicao->update(['status' => Edicao::ATIVA]);

            return ['success', 'Edição ativada com sucesso.'];
        });

        return redirect()->route('edicoes.show', $edicao)->with($resultado[0], $resultado[1]);
    }

    public function encerrar(Edicao $edicao): RedirectResponse
    {
        if (! $edicao->estaAtiva()) {
            return redirect()
                ->route('edicoes.show', $edicao)
                ->with('error', 'Somente edições ativas podem ser encerradas.');
        }

        $edicao->update(['status' => Edicao::ENCERRADA]);

        return redirect()
            ->route('edicoes.show', $edicao)
            ->with('success', 'Edição encerrada com sucesso.');
    }
}