<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Semana de Tecnologia')</title>
</head>
<body>
    <header class="topo">
    <div class="container topo__conteudo">
        <a class="marca" href="{{ route('home') }}">SETEC <small>Fatec Presidente Prudente</small></a>

        <nav class="menu" aria-label="Menu principal">
            <a href="{{ route('programacao.index') }}" class="{{ request()->routeIs('programacao.*') ? 'ativo' : '' }}">Programação</a>

            @auth
                <a href="{{ route('inscricoes.index') }}" class="{{ request()->routeIs('inscricoes.*') ? 'ativo' : '' }}">Minhas inscrições</a>
                <a href="{{ route('perfil.edit') }}" class="{{ request()->routeIs('perfil.*') ? 'ativo' : '' }}">Meu perfil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="menu__sair">Sair ({{ Str::of(auth()->user()->name)->before(' ') }})</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'ativo' : '' }}">Entrar</a>
                <a href="{{ route('participantes.create') }}" class="{{ request()->routeIs('participantes.*') ? 'ativo' : '' }}">Criar conta</a>
            @endauth
        </nav>
    </div>
    </header>

    <main>
        @include('partials._mensagens')
        @yield('content')
    </main>

<footer class="rodape">
    <div class="container">Fatec Presidente Prudente · Programação Web (Laravel) · Projeto integrador</div>
</footer>

@stack('scripts')

</body>
</html>
