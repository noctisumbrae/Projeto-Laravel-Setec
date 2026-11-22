<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">

    <title>Validar Certificado - SETEC</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 40px 16px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 16px #00000012;
        }

        h1 {
            color: #1f2937;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
        }

        button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
        }

        .sucesso {
            background: #dcfce7;
            color: #166534;
            padding: 16px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .erro {
            background: #fee2e2;
            color: #991b1b;
            padding: 16px;
            border-radius: 8px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <main class="container">

        <h1>Validação de Certificados</h1>

        <p>
            Informe o código de autenticidade
            para verificar um certificado da SETEC.
        </p>

        <form method="GET"
              action="{{ route('certificados.validar') }}">

            <label for="codigo">
                Código de validação
            </label>

            <input
                type="text"
                id="codigo"
                name="codigo"
                value="{{ is_string(request()->query('codigo')) ? request()->query('codigo') : '' }}"
                placeholder="Digite o código UUID"
                required
            >

            <button type="submit">
                Validar certificado
            </button>
        </form>

        @if($mensagem)
            <div class="erro">
                {{ $mensagem }}
            </div>
        @endif

        @if($certificado)
            <div class="sucesso">

                <h2>Certificado válido!</h2>

                <p>
                    <strong>Participante:</strong>
                    {{ $certificado->participante }}
                </p>

                <p>
                    <strong>Edição:</strong>
                    {{ $certificado->edicao }}
                </p>

                <p>
                    <strong>Carga horária:</strong>
                    {{ $certificado->carga_formatada }}
                </p>

                <p>
                    <strong>Data de emissão:</strong>
                    {{ $certificado->data_formatada }}
                </p>

            </div>
        @endif

    </main>
</body>
</html>
