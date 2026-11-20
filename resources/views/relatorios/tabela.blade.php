@extends('layouts.app')
@section('title', $titulo)
@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="mb-6">
        <a href="{{ route('relatorios.index') }}"
           class="text-blue-600 hover:underline">
            Voltar aos relatórios
        </a>
        <h2 class="text-2xl font-bold mt-4">
            {{ $titulo }}
        </h2>
        <p class="text-gray-600">
            {{ $descricao }}
        </p>
    </div>

    <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap items-end gap-3 mb-6">
        <div>
            <label class="block text-sm font-medium mb-1">
                Pesquisar
            </label>
            <input
                type="search"
                name="busca"
                value="{{ $busca }}"
                placeholder="Pesquisar no relatório..."
                class="border rounded-lg px-4 py-2">
        </div>
        @if($temEdicao)
            <div>
                <label class="block text-sm font-medium mb-1">
                    Edição
                </label>
                <select name="edicao" class="border rounded-lg px-4 py-2">
                    <option value="">Todas as edições</option>
                    @foreach($opcoesEdicoes as $opcao)
                        <option value="{{ $opcao }}" @selected($filtros['edicao'] === $opcao)>
                            {{ $opcao }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        @if($temAtividade)
            <div>
                <label class="block text-sm font-medium mb-1">
                    Atividade
                </label>
                <select
                    name="atividade"
                    class="border rounded-lg px-4 py-2">
                    <option value="">Todas as atividades</option>
                    @foreach($opcoesAtividades as $opcao)
                        <option value="{{ $opcao }}" @selected($filtros['atividade'] === $opcao)>
                            {{ $opcao }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        @if($campoSituacao !== null)
            <div>
                <label class="block text-sm font-medium mb-1">
                    Situação
                </label>

                <select name="situacao" class="border rounded-lg px-4 py-2">
                    <option value="">Todas as situações</option>
                    @foreach($opcoesSituacoes as $opcao)
                        <option value="{{ $opcao }}" @selected($filtros['situacao'] === $opcao)>
                            {{ $opcao }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        @if($campoData !== null)
            <div>
                <label class="block text-sm font-medium mb-1">
                    Data inicial
                </label>
                <input type="date" name="data_inicio" value="{{ $filtros['data_inicio'] }}" class="border rounded-lg px-4 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Data final
                </label>

                <input type="date" name="data_fim" value="{{ $filtros['data_fim'] }}" class="border rounded-lg px-4 py-2">
            </div>
        @endif

        <div class="flex flex-wrap gap-3">
            <button type="submit" class="bg-blue-700 text-white px-5 py-2 rounded-lg">
                Filtrar
            </button>

            <button type="submit" name="exportar" value="csv" class="bg-green-700 text-white px-5 py-2 rounded-lg">
                Exportar CSV
            </button>

            <a href="{{ url()->current() }}" class="border px-5 py-2 rounded-lg">
                Limpar
            </a>
        </div>
    </form>
    @include('relatorios.partials.tabela-dados')
</div>
@endsection