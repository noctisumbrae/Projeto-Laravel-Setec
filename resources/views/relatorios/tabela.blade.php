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
    <form method="GET"
          action="{{ url()->current() }}"
          class="flex flex-wrap gap-3 mb-6">
        <input
            type="search"
            name="busca"
            value="{{ $busca ?? '' }}"
            placeholder="Pesquisar participante..."
            class="border rounded-lg px-4 py-2"
        >
        <button
            type="submit"
            class="bg-blue-700 text-white px-5 py-2 rounded-lg">
            Pesquisar
        </button>
        <a href="{{ url()->current() }}"
           class="border px-5 py-2 rounded-lg">
            Limpar
        </a>
    </form>
    @include('relatorios.partials.tabela-dados')
</div>
@endsection