@extends('layouts.app')

@section('title', 'Programação')

@section('content')
    <h1>Programação</h1>

    @if(! $edicao)
        <div class="cartao vazio">
            <h2>Programação ainda não disponível</h2>
            <p class="texto-suave">Não há edição ativa no momento.</p>
        </div>
    @else
        <p class="texto-suave">{{ $edicao->nome }}</p>

        <div class="alerta alerta--aviso">
            As vagas só são confirmadas após a aprovação do pagamento. Escolher uma atividade não reserva a vaga.
        </div>

        <form method="GET" action="{{ route('programacao.index') }}" class="filtro">
            <label for="tipo">Mostrar:</label>
            <select id="tipo" name="tipo" onchange="this.form.submit()">
                <option value="">Palestras e minicursos</option>
                <option value="palestra" @selected($tipo === 'palestra')>Somente palestras</option>
                <option value="minicurso" @selected($tipo === 'minicurso')>Somente minicursos</option>
            </select>
            <noscript><button type="submit" class="botao botao--claro">Filtrar</button></noscript>
        </form>

        @forelse($porDia as $dia => $atividades)
            <h2 class="dia">{{ \Illuminate\Support\Carbon::parse($dia)->translatedFormat('l, d \d\e F') }}</h2>

            @foreach($atividades as $atividade)
                <article class="atividade">
                    <div class="atividade__hora">
                        {{ $atividade->inicio->format('H:i') }}<br>
                        <span class="atividade__meta">até {{ $atividade->fim->format('H:i') }}</span>
                    </div>
                    <div>
                        <div class="atividade__titulo">
                            <a href="{{ route('programacao.show', $atividade) }}">{{ $atividade->titulo }}</a>
                        </div>
                        <div class="atividade__meta">
                            {{ $atividade->rotulo_tipo }} · {{ $atividade->responsavel }} · {{ $atividade->local->nome }}
                        </div>
                    </div>
                    <div>@include('partials._vagas', ['atividade' => $atividade])</div>
                </article>
            @endforeach
        @empty
            <div class="cartao vazio mt">
                <p>Nenhuma atividade encontrada.</p>
            </div>
        @endforelse
    @endif
@endsection
