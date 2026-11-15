<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Detalhes da Presença</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container">
        <a href="{{ route('admin.attendance.index') }}" class="btn btn-sm btn-secondary mb-3">&larr; Voltar</a>
        <h3>{{ $activity->title }}</h3>

        @if(session('success'))
            <div class="alert alert-success fw-bold">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger fw-bold">{{ session('error') }}</div>
        @endif
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-success">
                    <div class="card-header bg-success text-white">Presentes ({{ $attendances->count() }})</div>
                    <ul class="list-group list-group-flush">
                        @forelse($attendances as $attendance)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    {{ $attendance->participant->name ?? 'Usuário Deletado' }}
                                    @if($attendance->is_manual)
                                        <span class="badge bg-warning text-dark">Manual</span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ $attendance->registered_at->format('H:i') }}</small>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Ninguém registrou presença ainda.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-danger">
                    <div class="card-header bg-danger text-white">Ausentes ({{ $absent->count() }})</div>
                    <ul class="list-group list-group-flush">
                        @forelse($absent as $user)
                            <li class="list-group-item">
                                <div class="mb-2 fw-bold">{{ $user->name }}</div>
                                <!-- FORMULÁRIO DE PRESENÇA MANUAL -->
                                <form method="POST" action="{{ route('admin.attendance.store-manual', $activity) }}" class="d-flex gap-2">
                                    @csrf
                                    <input type="hidden" name="participant_id" value="{{ $user->id }}">
                                    <input type="text" name="correction_justification" class="form-control form-control-sm" placeholder="Justificativa (ex: sem celular)" required minlength="5">
                                    <button type="submit" class="btn btn-sm btn-success text-nowrap">Registrar</button>
                                </form>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Nenhum ausente registrado.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>