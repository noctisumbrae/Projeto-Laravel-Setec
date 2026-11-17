@extends('layouts.app')

@section('title', 'Fazer inscrição')

@section('content')
    <div class="cartao cartao--estreito cartao--form"">
        <h2>Inscrição na {{ $edicao->nome }}</h2>

        <div class="grade grade--3">
            <div>
                <div class="dado__rotulo">Taxa única</div>
                <div class="dado__valor">R$ {{ number_format($edicao->valor_inscricao, 2, ',', '.') }}</div>
            </div>
            <div>
                <div class="dado__rotulo">Limite de palestras</div>
                <div class="dado__valor">{{ $edicao->limite_palestras ?? 'Sem limite' }}</div>
            </div>
            <div>
                <div class="dado__rotulo">Limite de minicursos</div>
                <div class="dado__valor">{{ $edicao->limite_minicursos ?? 'Sem limite' }}</div>
            </div>
        </div>

        <h3 class="mt">Como funciona</h3>
        <ol>
            <li>Você cria a inscrição e escolhe as atividades.</li>
            <li>Paga a taxa única por Pix e envia o comprovante.</li>
            <li>A organização aprova o pagamento e confirma as vagas.</li>
        </ol>
        <p class="campo__ajuda">Escolher uma atividade não garante a vaga. Se ela lotar antes da aprovação, você poderá trocar por outra sem pagar de novo.</p>

        @if($motivo)
            <div class="alerta alerta--aviso mt">{{ $motivo }}</div>
            <a class="botao botao--claro" href="{{ route('home') }}">Voltar</a>
        @else
            <form action="{{ route('inscricoes.store') }}" method="POST">
                @csrf
                <button type="submit" class="botao">Criar minha inscrição</button>
                <a class="botao botao--claro" href="{{ route('home') }}">Cancelar</a>
            </form>
        @endif
    </div>
@endsection
