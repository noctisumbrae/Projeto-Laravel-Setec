@extends('layouts.app')

@section('title', 'Minhas inscrições')

@section('content')
    <div class="pagina-cabecalho">
        <div>
            <p class="texto-suave">Área do participante</p>
            <h1>Minhas inscrições</h1>
            <p class="texto-suave">Acompanhe suas edições e atividades escolhidas.</p>
        </div>
        <a href="{{ route('inscricoes.create') }}" class="botao">Nova inscrição</a>
    </div>

    @if($inscricoes->isEmpty())
        <div class="cartao vazio">
            <h2>Nenhuma inscrição encontrada</h2>
            <p class="texto-suave">Quando houver uma edição aberta, você poderá começar por aqui.</p>
            <a href="{{ route('programacao.index') }}" class="botao botao--claro">Ver programação</a>
        </div>
    @else
        <div class="inscricao-lista">
            @foreach($inscricoes as $inscricao)
                <article class="cartao inscricao-linha">
                    <div>
                        <span class="selo {{ $inscricao->classe_status }}">{{ $inscricao->rotulo_status }}</span>
                        <h2>{{ $inscricao->edicao->nome }}</h2>
                        <p class="texto-suave">
                            {{ $inscricao->edicao->ano }} · {{ $inscricao->atividades_count }} atividade(s) escolhida(s)
                        </p>
                    </div>
                    <div class="inscricao-linha__acoes">
                        <a href="{{ route('inscricoes.show', $inscricao) }}" class="botao botao--claro">Ver detalhes</a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
@endsection
