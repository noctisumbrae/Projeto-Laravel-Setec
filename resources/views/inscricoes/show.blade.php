<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Inscrição (Equipe 3)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Detalhes da Inscrição</h2>
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Inscrição #{{ $inscricao->id }}</h5>
                
                <p class="card-text">
                    <strong>Usuário:</strong> {{ $inscricao->user->name }} ({{ $inscricao->user->email }})<br>
                    <strong>Edição:</strong> {{ $inscricao->edicao->nome }} ({{ $inscricao->edicao->ano }})<br>
                    <strong>Status:</strong> <span class="badge bg-{{ $inscricao->classe_status }}">{{ $inscricao->rotulo_status }}</span><br>
                    <strong>Criado em:</strong> {{ $inscricao->created_at->format('d/m/Y H:i') }}
                </p>
                
                <hr>
                
                <h6>Atividades ({{ $inscricao->atividades->count }})</h6>
                
                @if($inscricao->atividades->isEmpty())
                <p>Nenhuma atividade selecionada.</p>
                @else
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Atividade</th>
                            <th>Tipo</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inscricao->atividades as $atividade)
                        <tr>
                            <td>{{ $atividade->titulo }}</td>
                            <td>{{ $atividade->tipo_label }}</td>
                            <td>
                                <span class="badge bg-{{ $atividade->pivot->status === 'confirmada' ? 'success' : 'warning' }}">
                                    {{ ucfirst($atividade->pivot->status) }}
                                </span>
                            </td>
                            <td>
                                @can('inscricoes.deselecionar')
                                <form action="{{ route('inscricoes.deselecionar', [$inscricao->id, $atividade->id]) }}" method="POST" style="display:inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-link btn-sm text-danger">Remover</button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
        
        <div class="mt-4">
            @can('inscricoes.confirmar')
            <a href="{{ route('inscricoes.confirmar', $inscricao->id) }}" class="btn btn-success">Confirmar Inscrição</a>
            @endcan
            @can('inscricoes.cancelar')
            <a href="{{ route('inscricoes.cancelar', $inscricao->id) }}" class="btn btn-danger">Cancelar Inscrição</a>
            @endcan
        </div>
    </div>
</body>
</html>