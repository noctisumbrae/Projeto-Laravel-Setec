@extends('layouts.app')

@section('title', $titulo)

@section('content')
    <div class="relatorio-pagina">
        <div class="pagina-cabecalho">
            <div>
                <a href="{{ route('relatorios.index') }}" class="texto-link">Voltar aos relatórios</a>
                <h1>{{ $titulo }}</h1>
                <p class="texto-suave">{{ $descricao }}</p>
            </div>
        </div>

        <form method="GET" action="{{ url()->current() }}" class="relatorio-filtros">
            <div class="campo">
                <label for="busca">Pesquisar</label>
                <input id="busca" type="search" name="busca" value="{{ $busca }}" placeholder="Pesquisar no relatório...">
            </div>

            @if($temEdicao)
                <div class="campo">
                    <label for="edicao">Edição</label>
                    <select id="edicao" name="edicao">
                        <option value="">Todas as edições</option>
                        @foreach($opcoesEdicoes as $opcao)
                            <option value="{{ $opcao }}" @selected($filtros['edicao'] === $opcao)>{{ $opcao }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($temAtividade)
                <div class="campo">
                    <label for="atividade">Atividade</label>
                    <select id="atividade" name="atividade">
                        <option value="">Todas as atividades</option>
                        @foreach($opcoesAtividades as $opcao)
                            <option value="{{ $opcao }}" @selected($filtros['atividade'] === $opcao)>{{ $opcao }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($campoSituacao !== null)
                <div class="campo">
                    <label for="situacao">Situação</label>
                    <select id="situacao" name="situacao">
                        <option value="">Todas as situações</option>
                        @foreach($opcoesSituacoes as $opcao)
                            <option value="{{ $opcao }}" @selected($filtros['situacao'] === $opcao)>{{ $opcao }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($campoData !== null)
                <div class="campo">
                    <label for="data_inicio">Data inicial</label>
                    <input id="data_inicio" type="date" name="data_inicio" value="{{ $filtros['data_inicio'] }}">
                </div>
                <div class="campo">
                    <label for="data_fim">Data final</label>
                    <input id="data_fim" type="date" name="data_fim" value="{{ $filtros['data_fim'] }}">
                </div>
            @endif

            <div class="relatorio-filtros__acoes">
                <button type="submit" class="botao">Filtrar</button>
                <button type="submit" name="exportar" value="csv" class="botao botao--secundario">Exportar CSV</button>
                <a href="{{ url()->current() }}" class="botao botao--claro">Limpar</a>
            </div>
        </form>

        @include('relatorios.partials.tabela-dados')
    </div>
@endsection
