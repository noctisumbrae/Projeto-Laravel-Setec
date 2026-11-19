
@extends('layouts.app')

@section('content')
<main class="container">
    <h1>Cadastrar Local</h1>

    <p>Cadastre uma sala, laboratório ou auditório da SETEC.</p>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('locais.store') }}" method="POST">
        @csrf

        <label for="nome">Nome do local</label>
        <input
            type="text"
            id="nome"
            name="nome"
            value="{{ old('nome') }}"
            required
        >

        <label for="tipo">Tipo do local</label>
        <select id="tipo" name="tipo" required>
            <option value="">Selecione</option>

            <option value="sala"
                @selected(old('tipo') === 'sala')>
                Sala
            </option>

            <option value="laboratorio"
                @selected(old('tipo') === 'laboratorio')>
                Laboratório
            </option>

            <option value="auditorio"
                @selected(old('tipo') === 'auditorio')>
                Auditório
            </option>
        </select>

        <label for="capacidade">Capacidade</label>
        <input
            type="number"
            id="capacidade"
            name="capacidade"
            min="1"
            value="{{ old('capacidade') }}"
            required
        >

        <button type="submit">Cadastrar Local</button>

        <a href="{{ route('locais.index') }}">
            Voltar
        </a>
    </form>
</main>
@endsection
