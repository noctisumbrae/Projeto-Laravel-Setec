@extends('layouts.app')

@section('title', $atividade->titulo)

@section('content')
    <div class="cartao">
        <p>
            <span class="selo selo--cinza">{{ $atividade->rotulo_tipo }}</span>
            @include('partials._vagas', ['atividade' => $atividade])
        </p>
        <h1>{{ $atividade->titulo }}</h1>
        <p class="texto-suave">{{ $atividade->edicao->nome }}</p>

        <div class="grade grade--3 mt">
            <div>
                <div class="dado__rotulo">Data e horário</div>
                <div class="dado__valor">{{ $atividade->inicio->format('d/m/Y') }}, {{ $atividade->inicio->format('H:i') }} às {{ $atividade->fim->format('H:i') }}</div>
            </div>
            <div>
                <div class="dado__rotulo">Local</div>
                <div class="dado__valor">{{ $atividade->local->nome }}</div>
            </div>
            <div>
                <div class="dado__rotulo">{{ $atividade->tipo === 'minicurso' ? 'Instrutor' : 'Palestrante' }}</div>
                <div class="dado__valor">{{ $atividade->responsavel }}</div>
            </div>
        </div>

        @if($atividade->descricao)
            <h3 class="mt">Sobre a atividade</h3>
            <p>{{ $atividade->descricao }}</p>
        @endif

        <p class="campo__ajuda">Capacidade: {{ $atividade->capacidade }} · Vagas disponíveis: {{ $atividade->vagas_disponiveis }}</p>

        <a class="botao botao--claro" href="{{ route('programacao.index') }}">Voltar à programação</a>
    </div>
@endsection
