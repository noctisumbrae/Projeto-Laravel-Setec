
@extends('layouts.app')

@section('content')
<main class="container">
    <h1>Editar Local</h1>

    <p>Atualize as informações do local cadastrado.</p>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('locais.update', $local) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="nome">Nome do local</label>
        <input
            type="text"
            id="nome"
            name="nome"
            value="{{ old('nome', $local->nome) }}"
            required
        >

        <label for="tipo">Tipo do local</label>
        <select id="tipo" name="tipo" required>
            <option value="">Selecione</option>

            <option value="sala"
                @selected(old('tipo', $local->tipo) === 'sala')>
                Sala
            </option>

            <option value="laboratorio"
                @selected(old('tipo', $local->tipo) === 'laboratorio')>
                Laboratório
            </option>

            <option value="auditorio"
                @selected(old('tipo', $local->tipo) === 'auditorio')>
                Auditório
            </option>
        </select>

        <label for="capacidade">Capacidade</label>
        <input
            type="number"
            id="capacidade"
            name="capacidade"
            min="1"
            value="{{ old('capacidade', $local->capacidade) }}"
            required
        >

        <button type="submit">Salvar Alterações</button>

        <a href="{{ route('locais.index') }}">
            Cancelar
        </a>
    </form>
</main>
@endsection
