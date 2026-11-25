<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Certificado de Participação</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 12mm;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
            color: #222;
        }

        .certificado {
            position: relative;
            height: 165mm;
            border: 7px double #17395c;
            background: white;
            text-align: center;
            page-break-inside: avoid;
        }

        .instituicao {
            position: absolute;
            top: 10mm;
            left: 10mm;
            right: 10mm;
            font-size: 14px;
            line-height: 1.7;
            color: #555;
        }

        .titulo {
            position: absolute;
            top: 32mm;
            left: 10mm;
            right: 10mm;
            font-size: 36px;
            color: #17395c;
        }

        .introducao {
            position: absolute;
            top: 55mm;
            left: 10mm;
            right: 10mm;
            font-size: 16px;
        }

        .participante {
            position: absolute;
            top: 67mm;
            left: 10mm;
            right: 10mm;
            font-size: 26px;
            font-weight: bold;
        }

        .descricao {
            position: absolute;
            top: 88mm;
            left: 22mm;
            right: 22mm;
            font-size: 15px;
            line-height: 1.7;
        }

        .assinatura-esquerda {
            position: absolute;
            top: 116mm;
            left: 10%;
            width: 35%;
            border-top: 1px solid #333;
            padding-top: 4mm;
            font-size: 11px;
        }

        .assinatura-direita {
            position: absolute;
            top: 116mm;
            right: 10%;
            width: 35%;
            border-top: 1px solid #333;
            padding-top: 4mm;
            font-size: 11px;
        }

        .validacao {
            position: absolute;
            top: 128mm;
            left: 10mm;
            right: 10mm;
            font-size: 9px;
            line-height: 1.6;
            color: #555;
            word-wrap: break-word;
        }

        .codigo {
            font-weight: bold;
        }

        .endereco {
            margin-top: 3mm;
        }

        .rodape {
            position: absolute;
            bottom: 6mm;
            left: 10mm;
            right: 10mm;
            font-size: 10px;
            line-height: 1.5;
            color: #777;
        }
    </style>
</head>

<body>

    <div class="certificado">

        <div class="instituicao">
            Faculdade de Tecnologia de Presidente Prudente

            <div>
                {{ $edicao ?? 'Semana de Tecnologia' }}
            </div>
        </div>

        <div class="titulo">
            CERTIFICADO
        </div>

        <div class="introducao">
            Certificamos que
        </div>

        <div class="participante">
            {{ $participante }}
        </div>

        <div class="descricao">

            participou das atividades da

            <strong>
                {{ $edicao ?? 'Semana de Tecnologia' }}
            </strong>,

            totalizando uma carga horária de

            <strong>
                {{ intdiv($totalMinutos, 60) }}h
                {{ sprintf('%02d', $totalMinutos % 60) }}min
            </strong>,

            considerando as atividades com
            presença confirmada.

        </div>

        <div class="assinatura-esquerda">
            Coordenação do Evento
        </div>

        <div class="assinatura-direita">
            Representante da Instituição
        </div>

        <div class="validacao">

            <div>
                Código de autenticidade:

                <span class="codigo">
                    {{ $codigoValidacao }}
                </span>
            </div>

            <div class="endereco">
                Verifique em:

                <a href="{{ $urlValidacao }}">
                    {{ $urlValidacao }}
                </a>
            </div>

        </div>


	<div class="rodape">

	    <div>
	        Presidente Prudente, {{ now()->format('d/m/Y') }}
	    </div>

	    @if ($demonstrativo ?? true)

	        <div>
	            MODELO DEMONSTRATIVO - DOCUMENTO DE TESTE
	        </div>

	        <div>
	            Não possui validade como certificado oficial.
	        </div>

	    @else

	        <div>
	            Documento gerado pelo sistema SETEC.
	        </div>

	    @endif

	</div>


    </div>

</body>
</html>
