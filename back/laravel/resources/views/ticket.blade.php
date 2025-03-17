<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tiquet de Cinema</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .info { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $ticketData['movie'] }}</h2>
        <h4>Tiquet de Cinema</h4>
    </div>
    <div class="info">
        <p><strong>Seient:</strong> {{ $ticketData['seat']['row'] }}-{{ $ticketData['seat']['number'] }}</p>
        <p><strong>Hora:</strong> {{ $ticketData['time'] }}</p>
    </div>
</body>
</html>
