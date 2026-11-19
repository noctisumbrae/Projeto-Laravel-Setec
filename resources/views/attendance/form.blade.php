<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Presença</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">
    <div class="container">
        <div class="card shadow mx-auto" style="max-width: 400px;">
            <div class="card-body text-center p-4">
                <h4 class="mb-3">Registro de Presença</h4>
                <p class="text-muted">{{ $activity->title }}</p>
                <hr>

                @if(session('success'))
                    <div class="alert alert-success fw-bold">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger fw-bold">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('attendance.register', $token) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg w-100 mt-3">Confirmar Minha Presença</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>