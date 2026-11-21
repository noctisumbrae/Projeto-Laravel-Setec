
@extends('layouts.app')

@section('content')
<main class="container">
    <h1>Detalhes do Local</h1>

    <p>Informações do local cadastrado na SETEC.</p>

    <section>
        <h2>{{ $local->nome }}</h2>

        <p>
            <strong>Tipo:</strong>
            {{ ucfirst($local->tipo) }}
        </p>

        <p>
            <strong>Capacidade:</strong>
            {{ $local->capacidade }} pessoas
        </p>
 
        <p>
            <strong>Cadastrado em:</strong>
            {{ $local->created_at?->format('d/m/Y H:i') ?? 'Não informado' }}
        </p>

    </section>

    <a href="{{ route('locais.edit', ['local' => $local->id]) }}">
        Editar Local
    </a>

    <a href="{{ route('locais.index') }}">
        Voltar para a Listagem
    </a>
</main>
@endsection
