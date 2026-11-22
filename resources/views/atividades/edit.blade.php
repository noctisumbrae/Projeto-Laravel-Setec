@extends('layouts.app')

@section('title', 'Editar atividade')

@section('content')
<main class="container">
    <h1>Editar atividade</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('atividades.update', $atividade) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="edicao_id">Edição</label>
        <select id="edicao_id" name="edicao_id" required>
            @foreach ($edicoes as $edicao)
                <option value="{{ $edicao->id }}" @selected(old('edicao_id', $atividade->edicao_id) == $edicao->id)>
                    {{ $edicao->nome }} ({{ $edicao->ano }})
                </option>
            @endforeach
        </select>

        <label for="titulo">Título</label>
        <input type="text" id="titulo" name="titulo" value="{{ old('titulo', $atividade->titulo) }}" required>

        <label for="descricao">Descrição</label>
        <textarea id="descricao" name="descricao" required>{{ old('descricao', $atividade->descricao) }}</textarea>

        <label for="tipo">Tipo</label>
        <select id="tipo" name="tipo" required>
            <option value="palestra" @selected(old('tipo', $atividade->tipo) === 'palestra')>Palestra</option>
            <option value="minicurso" @selected(old('tipo', $atividade->tipo) === 'minicurso')>Minicurso</option>
        </select>

        <label for="responsavel">Responsável</label>
        <input type="text" id="responsavel" name="responsavel" value="{{ old('responsavel', $atividade->responsavel) }}" required>

        <label for="inicio">Início</label>
        <input type="datetime-local" id="inicio" name="inicio" value="{{ old('inicio', $atividade->inicio?->format('Y-m-d\\TH:i')) }}" required>

        <label for="duracao_minutos">Duração (minutos)</label>
        <input type="number" id="duracao_minutos" name="duracao_minutos" min="1" value="{{ old('duracao_minutos', $atividade->duracao_minutos) }}" required>

        <label for="capacidade">Capacidade</label>
        <input type="number" id="capacidade" name="capacidade" min="1" value="{{ old('capacidade', $atividade->capacidade) }}" required>

        <label for="local_id">Local</label>
        <select id="local_id" name="local_id" required>
            @foreach ($locais as $local)
                <option value="{{ $local->id }}" @selected(old('local_id', $atividade->local_id) == $local->id)>
                    {{ $local->nome }} ({{ $local->capacidade }} pessoas)
                </option>
            @endforeach
        </select>

        <button type="submit">Salvar alterações</button>
        <a href="{{ route('atividades.index') }}">Voltar</a>
    </form>
</main>
@endsection
