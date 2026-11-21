<p>
    <label>Nome<br>
        <input type="text" name="nome" value="{{ old('nome', $edicao->nome ?? '') }}" maxlength="150" required>
    </label>
</p>
<p>
    <label>Ano<br>
        <input type="number" name="ano" value="{{ old('ano', $edicao->ano ?? date('Y')) }}" min="2000" max="2100" required>
    </label>
</p>
<p>
    <label>Data de início<br>
        <input type="date" name="data_inicio" value="{{ old('data_inicio', isset($edicao) ? $edicao->data_inicio->format('Y-m-d') : '') }}" required>
    </label>
</p>
<p>
    <label>Data de término<br>
        <input type="date" name="data_fim" value="{{ old('data_fim', isset($edicao) ? $edicao->data_fim->format('Y-m-d') : '') }}" required>
    </label>
</p>
<p>
    <label>Descrição<br>
        <textarea name="descricao" rows="5" cols="60">{{ old('descricao', $edicao->descricao ?? '') }}</textarea>
    </label>
</p>
<button type="submit">Salvar</button>
<a href="{{ route('edicoes.index') }}">Cancelar</a>