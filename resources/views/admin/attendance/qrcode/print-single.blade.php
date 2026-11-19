<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Impressão QR Code</title>
    <style>
        @page { size: A4 portrait; margin: 20mm; }
        body { font-family: Arial, sans-serif; text-align: center; }
        .title { font-size: 24pt; margin-bottom: 20px; }
        .qr { margin: 30px 0; }
        .url { font-size: 10pt; color: #555; }
    </style>
</head>
<body onload="window.print()">
    <div class="title">{{ $activity->title }}</div>
    <p>Escaneie o código abaixo para registrar presença:</p>
    <div class="qr">
        {!! $qrcode !!}
    </div>
    <div class="url">{{ $url }}</div>
</body>
</html>