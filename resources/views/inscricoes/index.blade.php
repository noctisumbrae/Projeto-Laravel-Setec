<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscrições - Semana de Tecnologia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Gerenciamento de Inscrições (Equipe 3)</h2>
        
        <a href="{{ route('inscricoes.create') }}" class="btn btn-primary mb-3">Nova Inscrição</a>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Edição</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inscricoes as $inscricao)
                <tr>
                    <td>{{ $inscricao->user->name }}</td>
                    <td>{{ $inscricao->edicao->nome }} ({{ $inscricao->edicao->ano }})</td>
                    <td>
                        <span class="badge bg-{{ $inscricao->classe_status }}">
                            {{ $inscricao->rotulo_status }}
                        </span>
                    </td>
                    <td>
                        @can('inscricoes.edit')
                        <a href="{{ route('inscricoes.edit', $inscricao->id) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                        @endcan
                        @can('inscricoes.destroy')
                        <form action="{{ route('inscricoes.destroy', $inscricao->id) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
                        </form>
                        @endcan
                        @can('inscricoes.confirmar')
                        <form action="{{ route('inscricoes.confirmar', $inscricao->id) }}" method="POST" style="display:inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-success">Confirmar</button>
                        </form>
                        @endcan
                        @can('inscricoes.cancelar')
                        <form action="{{ route('inscricoes.cancelar', $inscricao->id) }}" method="POST" style="display:inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">Cancelar</button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        {{ $inscricoes->links() }}
    </div>
</body>
</html>