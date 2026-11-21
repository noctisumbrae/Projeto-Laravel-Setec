@extends('layouts.app')
@section('title', 'Relatórios')
@section('content')

<div class="max-w-7xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-2">
        Relatórios administrativos
    </h2>
    <p class="text-gray-600 mb-6">
        Selecione o relatório que deseja consultar.
    </p>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <a href="{{ route('relatorios.inscricoes') }}"
           class="bg-white border rounded-lg p-5 hover:shadow-md">
            <h3 class="font-bold text-lg">Inscrições</h3>
            <p class="text-gray-600 text-sm">
                Consulte os participantes inscritos.
            </p>
        </a>
        <a href="{{ route('relatorios.atividades') }}"
           class="bg-white border rounded-lg p-5 hover:shadow-md">
            <h3 class="font-bold text-lg">Atividades</h3>
            <p class="text-gray-600 text-sm">
                Consulte as atividades cadastradas.
            </p>
        </a>
        <a href="{{ route('relatorios.presencas') }}"
           class="bg-white border rounded-lg p-5 hover:shadow-md">
            <h3 class="font-bold text-lg">Presenças</h3>
            <p class="text-gray-600 text-sm">
                Consulte as presenças confirmadas.
            </p>
        </a>
        <a href="{{ route('relatorios.carga-horaria') }}"
           class="bg-white border rounded-lg p-5 hover:shadow-md">
            <h3 class="font-bold text-lg">Carga horária</h3>
            <p class="text-gray-600 text-sm">
                Consulte as horas acumuladas.
            </p>
        </a>
        <a href="{{ route('relatorios.certificados') }}"
           class="bg-white border rounded-lg p-5 hover:shadow-md">
            <h3 class="font-bold text-lg">Certificados</h3>
            <p class="text-gray-600 text-sm">
                Consulte os certificados emitidos.
            </p>
        </a>
        <a href="{{ route('relatorios.ocupacao') }}"
           class="bg-white border rounded-lg p-5 hover:shadow-md">
            <h3 class="font-bold text-lg">Ocupação</h3>
            <p class="text-gray-600 text-sm">
                Consulte a ocupação das atividades.
            </p>
        </a>
    </div>
</div>
@endsection