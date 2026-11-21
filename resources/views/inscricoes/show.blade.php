@extends('layouts.app')

@section('title', 'Minha inscrição')

@section('content')
    <h1>Minha inscrição</h1>
    <p class="texto-suave">{{ $inscricao->edicao->nome }}</p>

    <div class="cartao">
        <div class="grade grade--3">
            <div>
                <div class="dado__rotulo">Situação</div>
                <div class="dado__valor"><span class="selo {{ $inscricao->classe_status }}">{{ $inscricao->rotulo_status }}</span></div>
            </div>
            <div>
                <div class="dado__rotulo">Taxa de inscrição</div>
                <div class="dado__valor">R$ {{ number_format($inscricao->edicao->valor_inscricao, 2, ',', '.') }}</div>
            </div>
            <div>
                <div class="dado__rotulo">Inscrito em</div>
                <div class="dado__valor">{{ $inscricao->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        @if($inscricao->status === 'pendente')
            <div class="alerta alerta--aviso mt">
                Aguardando a aprovação do pagamento. As vagas das atividades abaixo só são garantidas depois dessa aprovação.
            </div>
        @elseif($inscricao->status === 'confirmada')
            <div class="alerta alerta--sucesso mt">Inscrição confirmada. Nos dias do evento, registre sua presença pelo QR Code de cada atividade.</div>
        @endif
    </div>

    <div class="cabecalho-secao">
        <h2>Atividades escolhidas</h2>
        @if($inscricao->editavel())
            <a class="botao" href="{{ route('inscricoes.atividades.edit', $inscricao) }}">
                {{ $atividades->isEmpty() ? 'Escolher atividades' : 'Alterar atividades' }}
            </a>
        @endif
    </div>

    @if($atividades->isEmpty())
        <div class="cartao vazio"><p>Você ainda não escolheu nenhuma atividade.</p></div>
    @else
        <div class="tabela-wrap">
            <table>
                <thead>
                    <tr><th>Atividade</th><th>Quando</th><th>Local</th><th>Situação da vaga</th></tr>
                </thead>
                <tbody>
                    @foreach($atividades as $atividade)
                        @php [$rotulo, $classe] = \App\Models\Inscricao::apresentacaoStatusAtividade($atividade->pivot->status); @endphp
                        <tr>
                            <td>
                                <a href="{{ route('programacao.show', $atividade) }}">{{ $atividade->titulo }}</a><br>
                                <span class="atividade__meta">{{ $atividade->rotulo_tipo }}</span>
                            </td>
                            <td>{{ $atividade->inicio->format('d/m H:i') }} às {{ $atividade->fim->format('H:i') }}</td>
                            <td>{{ $atividade->local->nome }}</td>
                            <td><span class="selo {{ $classe }}">{{ $rotulo }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <p class="mt"><a href="{{ route('inscricoes.index') }}">Voltar para minhas inscrições</a></p>
@endsection
