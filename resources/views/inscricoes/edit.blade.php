<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Inscrição (Equipe 3)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Editar Inscrição</h2>
        
        @if($inscricao->editavel())
        <p class="text-warning">⚠ Somente inscrições pendentes podem ser alteradas.</p>
        @else()
        <p class="text-danger">✗ Esta inscrição não pode ser alterada (status: {{ $inscricao->status }}).</p>
        @endif
        
        <form action="{{ route('inscricoes.update', $inscricao->id) }}" method="POST">
            @csrf @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Usuário</label>
                <select name="user_id" class="form-select" required>
                    <option value="">Selecione um usuário</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $inscricao->user_id === $user->id ? 'selected' : '' }}
                            {{ $user->id === $inscricao->user_id ? 'disabled' : '' }}>
                            {{ $user->name }} ({{ $user->getRotuloTipoAttribute }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Edição</label>
                <select name="edicao_id" class="form-select" required>
                    <option value="">Selecione uma edição</option>
                    @foreach ($edicoes as $edicao)
                        <option value="{{ $edicao->id }}" {{ $inscricao->edicao_id === $edicao->id ? 'selected' : '' }}>
                            {{ $edicao->nome }} ({{ $edicao->ano }})</option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Atualizar Inscrição</button>
            <a href="{{ route('inscricoes.show', $inscricao->id) }}" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</body>
</html>