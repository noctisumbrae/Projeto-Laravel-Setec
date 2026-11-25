@extends('layouts.app')

@section('title', 'Início')

@section('content')
    @php
        $edicao = $edicao ?? null;
        $inscricao = $inscricao ?? null;
    @endphp

    @if(! $edicao)
        <div class="cartao vazio">
            <h1>Nenhuma edição em andamento</h1>
            <p class="texto-suave">Assim que uma nova Semana de Tecnologia for aberta, ela aparecerá aqui.</p>
            <a class="botao botao--claro" href="{{ route('programacao.index') }}">Consultar programação</a>
        </div>
    @else
        @php
            $tipo = auth()->check() ? auth()->user()->tipo_participante : null;
            $motivo = $tipo ? $edicao->motivoInscricaoFechada($tipo) : null;
        @endphp

        <section class="destaque">
            <h1>{{ $edicao->nome }}</h1>
            <p class="texto-suave">{{ $edicao->data_inicio->format('d/m/Y') }} a {{ $edicao->data_fim->format('d/m/Y') }}</p>
            @if($edicao->descricao)<p>{{ $edicao->descricao }}</p>@endif

            <div class="acoes">
                @guest
                    <a class="botao" href="{{ route('participantes.create') }}">Criar conta para se inscrever</a>
                    <a class="botao botao--claro" href="{{ route('login') }}">Já tenho conta</a>
                @else
                    @if($inscricao)
                        <a class="botao" href="{{ route('inscricoes.show', $inscricao) }}">Ver minha inscrição</a>
                    @elseif($motivo)
                        <span class="botao botao--desativado">Inscrições fechadas</span>
                    @else
                        <a class="botao" href="{{ route('inscricoes.create') }}">Fazer minha inscrição</a>
                    @endif
                @endguest
                <a class="botao botao--claro" href="{{ route('programacao.index') }}">Ver programação</a>
            </div>

            @auth
                @if($motivo && ! $inscricao)
                    <div class="alerta alerta--aviso mt">{{ $motivo }}</div>
                @endif
            @endauth
        </section>

        <section class="grade grade--3">
            <div class="cartao">
                <div class="dado__rotulo">Taxa única de inscrição</div>
                <div class="dado__valor">R$ {{ number_format($edicao->valor_inscricao, 2, ',', '.') }}</div>
                <p class="campo__ajuda">Vale para qualquer quantidade de atividades. Pagamento via Pix.</p>
            </div>
            <div class="cartao">
                <div class="dado__rotulo">Inscrições para alunos da Fatec</div>
                <div class="dado__valor">{{ $edicao->descricaoPeriodo(\App\Models\User::TIPO_ALUNO) }}</div>
            </div>
            <div class="cartao">
                <div class="dado__rotulo">Inscrições para o público externo</div>
                <div class="dado__valor">{{ $edicao->descricaoPeriodo(\App\Models\User::TIPO_EXTERNO) }}</div>
            </div>
        </section>
    @endif
@endsection
