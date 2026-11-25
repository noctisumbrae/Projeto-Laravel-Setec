@extends('layouts.app')

@section('title', 'Nova inscrição')

@section('content')
    <div class="pagina-cabecalho">
        <div>
            <p class="texto-suave">Inscrição no evento</p>
            <h1>Confirmar participação</h1>
            <p class="texto-suave">Depois desta etapa, você poderá escolher suas atividades.</p>
        </div>
    </div>

    @if($motivo)
        <div class="alerta alerta--aviso" role="alert">{{ $motivo }}</div>
    @else
        <form class="cartao cartao--form" action="{{ route('inscricoes.store') }}" method="POST">
            @csrf

            <h2>{{ $edicao->nome }}</h2>
            <p class="texto-suave">
                {{ $edicao->data_inicio->format('d/m/Y') }} a {{ $edicao->data_fim->format('d/m/Y') }}
            </p>

            @if($edicao->descricao)
                <p>{{ $edicao->descricao }}</p>
            @endif

            <div class="grade grade--3 mt">
                <div>
                    <div class="dado__rotulo">Taxa única</div>
                    <div class="dado__valor">R$ {{ number_format($edicao->valor_inscricao, 2, ',', '.') }}</div>
                </div>
                <div>
                    <div class="dado__rotulo">Inscrições Fatec</div>
                    <div class="dado__valor">{{ $edicao->descricaoPeriodo(\App\Models\User::TIPO_ALUNO) }}</div>
                </div>
                <div>
                    <div class="dado__rotulo">Público externo</div>
                    <div class="dado__valor">{{ $edicao->descricaoPeriodo(\App\Models\User::TIPO_EXTERNO) }}</div>
                </div>
            </div>

            <div class="acoes--fim">
                <button type="submit" class="botao">Continuar para as atividades</button>
                <a class="botao botao--claro" href="{{ route('home') }}">Voltar</a>
            </div>
        </form>
    @endif
@endsection
