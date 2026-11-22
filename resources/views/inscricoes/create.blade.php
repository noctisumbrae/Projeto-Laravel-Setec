<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Inscrição (Equipe 3)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Nova Inscrição</h2>
        
        <form action="{{ route('inscricoes.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Usuário</label>
                <select name="user_id" class="form-select" required>
                    <option value="">Selecione um usuário</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->getRotuloTipoAttribute }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Edição</label>
                <select name="edicao_id" class="form-select" required>
                    <option value="">Selecione uma edição</option>
                    @foreach ($edicoes as $edicao)
                        <option value="{{ $edicao->id }}">{{ $edicao->nome }} ({{ $edicao->ano }})</option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Criar Inscrição</button>
            <a href="{{ route('inscricoes.index') }}" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</body>
</html>