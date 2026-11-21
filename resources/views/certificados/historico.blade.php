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

    .historico-container h1 {
        color: #17395c;
        margin-bottom: 15px;
    }

    .historico-tabela {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
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

    .botao-download {
        display: inline-block;
        padding: 8px 14px;
        background: #17395c;
        color: white;
        text-decoration: none;
        border-radius: 4px;
    }

    .botao-download:hover {
        background: #245785;
    }

    .historico-vazio {
        margin-top: 20px;
        padding: 20px;
        background: #f3f4f6;
    }
</style>

<div class="historico-container">

    <h1>Meus Certificados</h1>

    <p>
        Consulte os certificados emitidos em seu nome
        e faça o download dos documentos disponíveis.
    </p>

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
                                class="botao-download"
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