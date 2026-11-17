@extends('layouts.app')

@section('title', 'Minhas inscrições')

@section('content')
    <h1>Minhas inscrições</h1>

    @if($inscricoes->isEmpty())
        <div class="cartao vazio">
            <h2>Você ainda não tem inscrições</h2>
            <p class="texto-suave">Faça sua inscrição na edição atual e escolha as atividades.</p>
            <a class="botao" href="{{ route('inscricoes.create') }}">Fazer inscrição</a>
        </div>
    @else
        <div class="tabela-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Edição</th>
                        <th>Inscrito em</th>
                        <th>Atividades</th>
                        <th>Situação</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inscricoes as $inscricao)
                        <tr>
                            <td>{{ $inscricao->edicao->nome }}</td>
                            <td>{{ $inscricao->created_at->format('d/m/Y') }}</td>
                            <td>{{ $inscricao->atividades_count }}</td>
                            <td><span class="selo {{ $inscricao->classe_status }}">{{ $inscricao->rotulo_status }}</span></td>
                            <td><a class="botao botao--claro" href="{{ route('inscricoes.show', $inscricao) }}">Abrir</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
