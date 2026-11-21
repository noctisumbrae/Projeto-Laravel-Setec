
@extends('layouts.app')

@section('content')
<main class="container">
    <h1>Cadastrar Atividade</h1>

    <p>Preencha os dados da palestra ou minicurso.</p>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('atividades.store') }}" method="POST">
        @csrf

        <label for="titulo">Título</label>
        <input type="text" id="titulo" name="titulo"
               value="{{ old('titulo') }}" required>

        <label for="descricao">Descrição</label>
        <textarea id="descricao" name="descricao" required>{{ old('descricao') }}</textarea>

        <label for="tipo">Tipo</label>
        <select id="tipo" name="tipo" required>
            <option value="">Selecione</option>
            <option value="palestra" @selected(old('tipo') === 'palestra')>Palestra</option>
            <option value="minicurso" @selected(old('tipo') === 'minicurso')>Minicurso</option>
        </select>

        <label for="responsavel">Responsável</label>
        <input type="text" id="responsavel" name="responsavel"
               value="{{ old('responsavel') }}" required>

        <label for="data">Data</label>
        <input type="date" id="data" name="data"
               value="{{ old('data') }}" required>

        <label for="horario">Horário de início</label>
        <input type="time" id="horario" name="horario"
               value="{{ old('horario') }}" required>

        <label for="duracao">Duração (minutos)</label>
        <input type="number" id="duracao" name="duracao"
               value="{{ old('duracao') }}" min="1" required>

        <label for="capacidade">Capacidade de participantes</label>
        <input type="number" id="capacidade" name="capacidade"
               value="{{ old('capacidade') }}" min="1" required>

        <label for="local_id">Local</label>
        <select id="local_id" name="local_id" required>
            <option value="">Selecione um local</option>
            @foreach ($locais as $local)
                <option value="{{ $local->id }}"
                    @selected(old('local_id') == $local->id)>
                    {{ $local->nome }} ({{ $local->capacidade }} pessoas)
                </option>
            @endforeach
        </select>

        <button type="submit">Cadastrar Atividade</button>

        <a href="{{ route('atividades.index') }}">
            Voltar
        </a>
    </form>
</main>
@endsection
