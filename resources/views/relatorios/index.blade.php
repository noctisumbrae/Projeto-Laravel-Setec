@extends('layouts.app')

@section('title', 'Relatórios')

@section('content')
    <div class="pagina-cabecalho">
        <div>
            <p class="texto-suave">Visão administrativa</p>
            <h1>Relatórios</h1>
            <p class="texto-suave">Escolha uma consulta para acompanhar os dados do evento.</p>
        </div>
    </div>

    <div class="relatorio-grid">
        <a href="{{ route('relatorios.inscricoes') }}" class="relatorio-card">
            <h2>Inscrições</h2>
            <p>Consulte os participantes inscritos.</p>
        </a>
        <a href="{{ route('relatorios.atividades') }}" class="relatorio-card">
            <h2>Atividades</h2>
            <p>Consulte as atividades cadastradas.</p>
        </a>
        <a href="{{ route('relatorios.presencas') }}" class="relatorio-card">
            <h2>Presenças</h2>
            <p>Consulte as presenças confirmadas.</p>
        </a>
        <a href="{{ route('relatorios.carga-horaria') }}" class="relatorio-card">
            <h2>Carga horária</h2>
            <p>Consulte as horas acumuladas.</p>
        </a>
        <a href="{{ route('relatorios.certificados') }}" class="relatorio-card">
            <h2>Certificados</h2>
            <p>Consulte os certificados emitidos.</p>
        </a>
        <a href="{{ route('relatorios.ocupacao') }}" class="relatorio-card">
            <h2>Ocupação</h2>
            <p>Consulte a ocupação das atividades.</p>
        </a>
    </div>
@endsection
