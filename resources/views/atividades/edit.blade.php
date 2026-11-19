
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Atividade</title>
</head>
<body>
    <h1>Editar Atividade</h1>

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

        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo"
            value="{{ old('titulo', $atividade->titulo) }}" required>

        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" required>{{ old('descricao', $atividade->descricao) }}</textarea>

        <label for="tipo">Tipo:</label>
        <select id="tipo" name="tipo" required>
            <option value="palestra" @selected(old('tipo', $atividade->tipo) == 'palestra')>Palestra</option>
            <option value="minicurso" @selected(old('tipo', $atividade->tipo) == 'minicurso')>Minicurso</option>
        </select>

        <label for="responsavel">Responsável:</label>
        <input type="text" id="responsavel" name="responsavel"
            value="{{ old('responsavel', $atividade->responsavel) }}" required>

        <label for="data">Data:</label>
        <input type="date" id="data" name="data"
            value="{{ old('data', $atividade->data->format('Y-m-d')) }}" required>

        <label for="horario">Horário:</label>
        <input type="time" id="horario" name="horario"
            value="{{ old('horario', substr($atividade->horario, 0, 5)) }}" required>

        <label for="duracao">Duração (minutos):</label>
        <input type="number" id="duracao" name="duracao" min="1"
            value="{{ old('duracao', $atividade->duracao) }}" required>

        <label for="capacidade">Capacidade:</label>
        <input type="number" id="capacidade" name="capacidade" min="1"
            value="{{ old('capacidade', $atividade->capacidade) }}" required>

        <label for="local_id">Local:</label>
        <select id="local_id" name="local_id" required>
            @foreach ($locais as $local)
                <option value="{{ $local->id }}"
                    @selected(old('local_id', $atividade->local_id) == $local->id)>
                    {{ $local->nome }} ({{ $local->capacidade }} pessoas)
                </option>
            @endforeach
        </select>

        <button type="submit">Salvar Alterações</button>
    </form>

    <a href="{{ route('atividades.index') }}">Voltar</a>
</body>
</html>
