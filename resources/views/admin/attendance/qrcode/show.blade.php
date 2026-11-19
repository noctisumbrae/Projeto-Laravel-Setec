<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>QR Code - {{ $activity->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container bg-white p-4 rounded shadow" style="max-width: 600px; text-align: center;">
        <h3>QR Code de Presença</h3>
        <p class="text-muted">{{ $activity->title }}</p>
        
        <div class="my-4">
            {!! $qrcode !!}
        </div>

        <div class="d-flex justify-content-center gap-2">
            <a href="{{ route('admin.attendance.qrcode.print', $activity) }}" target="_blank" class="btn btn-primary">Imprimir A4</a>
        </div>
        <div class="mt-3 text-muted small">{{ $url }}</div>
    </div>
</body>
</html>