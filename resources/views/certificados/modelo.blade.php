
@extends('layouts.app')

@section('title', 'Modelo de Certificado')

@section('content')

<style>
    .certificados-container {
        font-family: Arial, sans-serif;
        max-width: 1050px;
        margin: 20px auto;
    }

    .certificados-acoes {
        margin-bottom: 20px;
    }

    .certificados-acoes button,
    .certificados-acoes a {
        padding: 10px 18px;
        border: 1px solid #ccc;
        background: #eee;
        color: #222;
        text-decoration: none;
        cursor: pointer;
        margin-right: 10px;
    }

    .certificado {
        box-sizing: border-box;
        width: 100%;
        min-height: 600px;
        padding: 45px;
        border: 8px double #17395c;
        background: white;
        color: #222;
        text-align: center;

        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .certificado-instituicao {
        font-size: 16px;
        color: #555;
    }

    .certificado h1 {
        font-size: 38px;
        color: #17395c;
        margin: 25px 0;
    }

    .certificado-nome {
        font-size: 27px;
        font-weight: bold;
        margin: 22px 0;
    }

    .certificado-texto {
        font-size: 18px;
        line-height: 1.8;
        max-width: 750px;
        margin: 0 auto;
    }

    .certificado-assinaturas {
        display: flex;
        justify-content: space-around;
        gap: 30px;
        margin-top: 55px;
    }

    .certificado-assinatura {
        width: 40%;
        border-top: 1px solid #333;
        padding-top: 10px;
        font-size: 14px;
    }

    /* Data atual do certificado */
    .certificado-data {
        font-size: 14px;
        color: #444;
        margin-top: 28px;
    }

    .certificado-aviso {
        font-size: 12px;
        color: #777;
        margin-top: 20px;
    }

    @media print {
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        body {
            margin: 0;
        }

        body > header,
        .certificados-acoes {
            display: none;
        }

        .certificados-container {
            margin: 0;
            max-width: none;
        }

        .certificado {
            width: 100%;
            min-height: 180mm;
            padding: 15mm;
            box-shadow: none;
            print-color-adjust: exact;
        }
    }
</style>

<div class="certificados-container">

    <div class="certificados-acoes">
        <a href="{{ route('certificados.index') }}">
            Voltar
        </a>

        <button type="button" onclick="window.print()">
            Imprimir / Salvar como PDF
        </button>
    </div>

    <div class="certificado">

        <div>
            <div class="certificado-instituicao">
                Faculdade de Tecnologia de Presidente Prudente
                <p>Semana de Tecnologia</p>
            </div>

            <h1>CERTIFICADO</h1>

            <p>Certificamos que</p>

            <div class="certificado-nome">
                {{ $participante }}
            </div>

            <div class="certificado-texto">
                participou das atividades da
                <strong>Semana de Tecnologia da Fatec
                Presidente Prudente</strong>,
                totalizando uma carga horária de
                <strong>
                    {{ intdiv($totalMinutos, 60) }}h
                    {{ sprintf('%02d', $totalMinutos % 60) }}min
                </strong>,
                considerando as atividades com
                presença confirmada.
            </div>
        </div>

        <div>
            <div class="certificado-assinaturas">

                <div class="certificado-assinatura">
                    Coordenação do Evento
                </div>

                <div class="certificado-assinatura">
                    Representante da Instituição
                </div>

            </div>

            <div class="certificado-data">
                Presidente Prudente,
                {{ now('America/Sao_Paulo')->format('d/m/Y') }}
            </div>

            <div class="certificado-aviso">
                MODELO DEMONSTRATIVO - DADOS FICTÍCIOS
                <p>Não possui validade como certificado oficial.</p>
            </div>
        </div>

    </div>

</div>

@endsection
