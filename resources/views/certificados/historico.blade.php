@extends('layouts.app')

@section('title', 'Meus Certificados')

@section('content')

<style>
    .historico-container {
        max-width: 1000px;
        margin: 30px auto;
        padding: 20px;
        font-family: Arial, sans-serif;
    }

    .historico-container h1,
    .historico-container h2 {
        color: #17395c;
    }

    .historico-container h2 {
        margin-top: 30px;
    }

    .historico-tabela {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .historico-tabela th,
    .historico-tabela td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .historico-tabela th {
        background: #f3f4f6;
    }

    .botao-certificado {
        display: inline-block;
        padding: 9px 14px;
        background: #17395c;
        color: white;
        text-decoration: none;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
    }

    .botao-certificado:hover {
        background: #245785;
    }

    .mensagem {
        padding: 14px;
        margin: 20px 0;
        border-radius: 4px;
    }

    .sucesso {
        background: #e5f5e9;
        color: #185b30;
    }

    .erro {
        background: #fce8e8;
        color: #8a2020;
    }

    .historico-vazio {
        padding: 15px;
        background: #f3f4f6;
        margin-top: 15px;
    }
</style>

<div class="historico-container">

    <h1>Meus Certificados</h1>

    <p>
        Consulte seus certificados, solicite emissões disponíveis
        e faça o download dos documentos emitidos.
    </p>

    @if (session('sucesso'))
        <div class="mensagem sucesso">
            {{ session('sucesso') }}
        </div>
    @endif

    @if (session('erro'))
        <div class="mensagem erro">
            {{ session('erro') }}
        </div>
    @endif

    <h2>Certificados disponíveis para emissão</h2>

    @if ($inscricoesDisponiveis->isEmpty())

        <div class="historico-vazio">
            Nenhuma inscrição com horas válidas está disponível
            para emissão neste momento.
        </div>

    @else

        <table class="historico-tabela">
            <thead>
                <tr>
                    <th>Edição do evento</th>
                    <th>Ação</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($inscricoesDisponiveis as $inscricao)
                    <tr>
                        <td>
                            {{ $inscricao->edicao }}
                        </td>

                        <td>
                            <form
                                method="POST"
                                action="{{ route('certificados.historico.emitir', $inscricao->id) }}"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="botao-certificado"
                                >
                                    Emitir certificado
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @endif

    <h2>Histórico de certificados emitidos</h2>

    @if ($certificados->isEmpty())

        <div class="historico-vazio">
            Você ainda não possui certificados emitidos.
        </div>

    @else

        <table class="historico-tabela">
            <thead>
                <tr>
                    <th>Edição</th>
                    <th>Carga horária</th>
                    <th>Data de emissão</th>
                    <th>Download</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($certificados as $certificado)
                    <tr>
                        <td>
                            {{ $certificado->edicao }}
                        </td>

                        <td>
                            {{ intdiv($certificado->carga_horaria_minutos, 60) }}h
                            {{ $certificado->carga_horaria_minutos % 60 }}min
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($certificado->emitido_em)->format('d/m/Y') }}
                        </td>

                        <td>
                            <a
                                class="botao-certificado"
                                href="{{ route('certificados.historico.pdf', $certificado->id) }}"
                            >
                                Baixar PDF
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @endif

</div>

@endsection
