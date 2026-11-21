
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Semana de Tecnologia')</title>
    <link rel="stylesheet" href="{{ asset('css/estilo.css') }}">
</head>
<body>
    <header class="cabecalho">
        <h1>Semana de Tecnologia</h1>

        <nav class="menu">
            <a href="{{ route('locais.index') }}">
                Locais
            </a>

            <a href="{{ route('atividades.index') }}">
                Palestras e Minicursos
            </a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>
