@extends('layouts.app')

@section('title', 'Escolher atividades')

@section('content')
    <h1>Escolher atividades</h1>
    <p class="texto-suave">{{ $edicao->nome }}</p>

    <div class="alerta alerta--aviso">
        Escolher uma atividade <strong>não reserva a vaga</strong>. A vaga é confirmada depois da aprovação do pagamento.
        Você pode alterar a escolha enquanto a inscrição estiver pendente.
    </div>

    @if($errors->has('atividades') || $errors->has('atividades.*'))
        <div class="alerta alerta--erro" role="alert">
            <strong>Não foi possível salvar:</strong>
            <ul>
                @foreach($errors->get('atividades') as $mensagem)
                    @foreach((array) $mensagem as $texto)<li>{{ $texto }}</li>@endforeach
                @endforeach
                @foreach($errors->get('atividades.*') as $mensagens)
                    @foreach($mensagens as $texto)<li>{{ $texto }}</li>@endforeach
                @endforeach
            </ul>
        </div>
    @endif

    <div class="alerta alerta--erro" id="aviso-conflito" role="alert" hidden>
        Há atividades marcadas com horários que se sobrepõem. Desmarque uma delas.
    </div>

    <form action="{{ route('inscricoes.atividades.update', $inscricao) }}" method="POST">
        @csrf
        @method('PUT')

        @php
            $marcadas = collect(old('atividades', $selecionadas))->map(fn ($v) => (int) $v)->all();
        @endphp

        @forelse($porDia as $dia => $atividades)
            <h2 class="dia">{{ \Illuminate\Support\Carbon::parse($dia)->translatedFormat('l, d \d\e F') }}</h2>

            @foreach($atividades as $atividade)
                @php
                    $statusAtual = $statusAtuais[$atividade->id] ?? null;
                    $confirmada = $statusAtual === 'confirmada';
                    $estaMarcada = $confirmada || in_array($atividade->id, $marcadas, true);
                    $semVaga = $atividade->vagas_disponiveis <= 0 && ! in_array($statusAtual, ['selecionada', 'confirmada'], true);
                    $bloqueada = $confirmada || $semVaga;
                @endphp

                <label class="atividade atividade-opcao {{ $bloqueada ? 'bloqueada' : '' }}" for="atividade-{{ $atividade->id }}">
                    <input type="checkbox" id="atividade-{{ $atividade->id }}" name="atividades[]" value="{{ $atividade->id }}"
                           data-tipo="{{ $atividade->tipo }}"
                           data-inicio="{{ $atividade->inicio->timestamp }}"
                           data-fim="{{ $atividade->fim->timestamp }}"
                           {{ $estaMarcada ? 'checked' : '' }} {{ $bloqueada ? 'disabled' : '' }}>
                    <div class="atividade__hora">
                        {{ $atividade->inicio->format('H:i') }}<br>
                        <span class="atividade__meta">até {{ $atividade->fim->format('H:i') }}</span>
                    </div>
                    <div>
                        <div class="atividade__titulo">{{ $atividade->titulo }}</div>
                        <div class="atividade__meta">
                            {{ $atividade->rotulo_tipo }} · {{ $atividade->responsavel }} · {{ $atividade->local->nome }}
                        </div>
                        @if($confirmada)
                            <div class="campo__ajuda">Vaga já confirmada: não pode ser removida.</div>
                        @elseif($statusAtual === 'lotada')
                            <div class="campo__erro">Esta atividade lotou. Escolha outra, sem novo pagamento.</div>
                        @endif
                    </div>
                    <div>@include('partials._vagas', ['atividade' => $atividade])</div>
                </label>
            @endforeach
        @empty
            <div class="cartao vazio"><p>Esta edição ainda não tem atividades cadastradas.</p></div>
        @endforelse

        <div class="contador">
            <div>
                Palestras: <strong id="cont-palestra">0</strong>@if($edicao->limite_palestras !== null) / {{ $edicao->limite_palestras }}@endif
                &nbsp;·&nbsp;
                Minicursos: <strong id="cont-minicurso">0</strong>@if($edicao->limite_minicursos !== null) / {{ $edicao->limite_minicursos }}@endif
            </div>
            <div>
                <button type="submit" class="botao">Salvar atividades</button>
                <a class="botao botao--claro" href="{{ route('inscricoes.show', $inscricao) }}">Cancelar</a>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    // Apoio visual no navegador. A validação oficial é feita no servidor (InscricaoAtividadeController).
    (function () {
        const caixas = Array.from(document.querySelectorAll('input[name="atividades[]"]'));
        const aviso = document.getElementById('aviso-conflito');

        function atualizar() {
            const marcadas = caixas.filter(c => c.checked);

            ['palestra', 'minicurso'].forEach(tipo => {
                document.getElementById('cont-' + tipo).textContent =
                    marcadas.filter(c => c.dataset.tipo === tipo).length;
            });

            caixas.forEach(c => c.closest('.atividade-opcao').classList.remove('conflito'));

            let achou = false;
            marcadas.forEach((a, i) => {
                marcadas.slice(i + 1).forEach(b => {
                    if (+a.dataset.inicio < +b.dataset.fim && +a.dataset.fim > +b.dataset.inicio) {
                        achou = true;
                        a.closest('.atividade-opcao').classList.add('conflito');
                        b.closest('.atividade-opcao').classList.add('conflito');
                    }
                });
            });
            aviso.hidden = !achou;
        }

        caixas.forEach(c => c.addEventListener('change', atualizar));
        atualizar();
    })();
</script>
@endpush
