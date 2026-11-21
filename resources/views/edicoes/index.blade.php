@extends('layouts.app')

@section('content')
    <h1>Edições da Semana de Tecnologia</h1>

    @include('edicoes._mensagens')

    <p>
        <a href="{{ route('edicoes.create') }}">+ Nova edição</a>
    </p>

    <p>
        Filtrar:
        <a href="{{ route('edicoes.index') }}">Todas</a> |
        <a href="{{ route('edicoes.index', ['status' => 'ativa']) }}">Ativa</a> |
        <a href="{{ route('edicoes.index', ['status' => 'planejada']) }}">Planejadas</a> |
        <a href="{{ route('edicoes.index', ['status' => 'encerrada']) }}">Anteriores (encerradas)</a>
    </p>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Ano</th>
                <th>Nome</th>
                <th>Período</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($edicoes as $edicao)
                <tr>
                    <td>{{ $edicao->ano }}</td>
                    <td>{{ $edicao->nome }}</td>
                    <td>{{ $edicao->data_inicio->format('d/m/Y') }} a {{ $edicao->data_fim->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($edicao->status) }}</td>
                    <td>
                        <a href="{{ route('edicoes.show', $edicao) }}">Consultar</a>
                        | <a href="{{ route('edicoes.regras-inscricao', $edicao) }}">Regras de inscrição</a>
                        @unless ($edicao->estaEncerrada())
                            | <a href="{{ route('edicoes.edit', $edicao) }}">Editar</a>
                        @endunless
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Nenhuma edição encontrada.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:12px;">{{ $edicoes->links() }}</div>
@endsection
