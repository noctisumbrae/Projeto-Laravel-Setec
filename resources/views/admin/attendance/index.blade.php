<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel de Presenças</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container">
        <h2 class="mb-4">Painel de Presenças</h2>
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>Atividade</th>
                            <th>Data</th>
                            <th class="text-center">Presentes</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $activity)
                        <tr>
                            <td>{{ $activity->title }}</td>
                            <td>{{ $activity->starts_at ? $activity->starts_at->format('d/m/Y H:i') : '-' }}</td>
                            <td class="text-center"><span class="badge bg-success">{{ $activity->attendances_count }}</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.attendance.by-activity', $activity) }}" class="btn btn-sm btn-outline-primary">Ver Detalhes</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>