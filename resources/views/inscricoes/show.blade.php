@extends('layouts.app')

@section('title', 'Detalhes da inscrição')

@section('content')
    <div class="pagina-cabecalho">
        <div>
            <p class="texto-suave">Área do participante</p>
            <h1>{{ $inscricao->edicao->nome }}</h1>
            <p class="texto-suave">Inscrição criada em {{ $inscricao->created_at->format('d/m/Y H:i') }}.</p>
        </div>
        <a href="{{ route('inscricoes.index') }}" class="botao botao--claro">Voltar</a>
    </div>

    <div class="grade grade--3">
        <section class="cartao">
            <div class="dado__rotulo">Situação da inscrição</div>
            <div class="dado__valor"><span class="selo {{ $inscricao->classe_status }}">{{ $inscricao->rotulo_status }}</span></div>
        </section>
        <section class="cartao">
            <div class="dado__rotulo">Edição</div>
            <div class="dado__valor">{{ $inscricao->edicao->ano }}</div>
        </section>
        <section class="cartao">
            <div class="dado__rotulo">Atividades escolhidas</div>
            <div class="dado__valor">{{ $atividades->count() }}</div>
        </section>
    </div>

    <section class="cartao mt">
        <div class="pagina-cabecalho">
            <div>
                <h2>Atividades escolhidas</h2>
                <p class="texto-suave">A confirmação de vagas depende do pagamento e da disponibilidade.</p>
            </div>
            @if($inscricao->editavel() && Route::has('inscricoes.atividades.edit'))
                <a href="{{ route('inscricoes.atividades.edit', $inscricao) }}" class="botao">Ajustar atividades</a>
            @endif
        </div>

        @if($atividades->isEmpty())
            <div class="vazio">
                <p class="texto-suave">Nenhuma atividade foi escolhida ainda.</p>
            </div>
        @else
            <div class="tabela-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Atividade</th>
                            <th>Data e horário</th>
                            <th>Local</th>
                            <th>Situação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($atividades as $atividade)
                            @php
                                $statusAtividade = \App\Models\Inscricao::apresentacaoStatusAtividade($atividade->pivot->status);
                                $rotuloAtividade = $statusAtividade[0];
                                $classeAtividade = $statusAtividade[1];
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $atividade->titulo }}</strong>
                                    <div class="campo__ajuda">{{ $atividade->rotulo_tipo }}</div>
                                </td>
                                <td>{{ $atividade->inicio->format('d/m/Y H:i') }}–{{ $atividade->fim->format('H:i') }}</td>
                                <td>{{ $atividade->local->nome }}</td>
                                <td><span class="selo {{ $classeAtividade }}">{{ $rotuloAtividade }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
