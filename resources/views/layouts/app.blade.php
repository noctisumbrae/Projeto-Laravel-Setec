<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Semana de Tecnologia')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="cabecalho">
        <h1>Semana de Tecnologia</h1>

        <nav class="menu" aria-label="Menu principal">
            <a href="{{ route('edicoes.index') }}">Edições</a>
            <a href="{{ route('locais.index') }}">Locais</a>
            <a href="{{ route('atividades.index') }}">Atividades</a>
            <a href="{{ route('speakers.index') }}">Palestrantes</a>
            <a href="{{ route('inscricoes.index') }}">Inscrições</a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>
