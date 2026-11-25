<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Semana de Tecnologia')</title>
    <link rel="stylesheet" href="{{ asset('css/sistema.css') }}">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body>
    <a class="site-skip" href="#conteudo">Pular para o conteúdo</a>

    <header class="topo">
        <div class="container topo__conteudo">
            <a class="marca" href="{{ route('home') }}">
                <span>SETEC</span>
                <small>Fatec Presidente Prudente</small>
            </a>

            <nav class="menu" aria-label="Menu principal">
                <a href="{{ route('programacao.index') }}" class="{{ request()->routeIs('programacao.*') ? 'ativo' : '' }}">Programação</a>

                @if (Route::has('certificados.validar'))
                    <a href="{{ route('certificados.validar') }}" class="{{ request()->routeIs('certificados.validar') ? 'ativo' : '' }}">Validar certificado</a>
                @endif

                @auth
                    <a href="{{ route('inscricoes.index') }}" class="{{ request()->routeIs('inscricoes.*') ? 'ativo' : '' }}">Minhas inscrições</a>
                    <a href="{{ route('perfil.edit') }}" class="{{ request()->routeIs('perfil.*') ? 'ativo' : '' }}">Meu perfil</a>
                    @if (Route::has('certificados.historico'))
                        <a href="{{ route('certificados.historico') }}" class="{{ request()->routeIs('certificados.historico*') ? 'ativo' : '' }}">Meus certificados</a>
                    @endif

                    @if (auth()->user()->ehAdmin())
                        @if (Route::has('edicoes.index'))
                            <a href="{{ route('edicoes.index') }}" class="{{ request()->routeIs('edicoes.*') ? 'ativo' : '' }}">Administração</a>
                        @endif
                        @if (Route::has('admin.attendance.index'))
                            <a href="{{ route('admin.attendance.index') }}" class="{{ request()->routeIs('admin.attendance.*') ? 'ativo' : '' }}">Presenças</a>
                        @endif
                        @if (Route::has('relatorios.index'))
                            <a href="{{ route('relatorios.index') }}" class="{{ request()->routeIs('relatorios.*') ? 'ativo' : '' }}">Relatórios</a>
                        @endif
                    @endif

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

    <main id="conteudo" class="site-main">
        @include('partials._mensagens')
        <div class="page-shell">
            @yield('content')
        </div>
    </main>

    <footer class="rodape">
        <div class="container">Fatec Presidente Prudente · Programação Web · Projeto integrador</div>
    </footer>

    @stack('scripts')
</body>
</html>
