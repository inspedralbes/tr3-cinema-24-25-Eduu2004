<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Movie Ticket</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif; 
            background-color: #f5f5f5;
        }

        .ticket {
            width: 800px;      
            height: 300px;    
            margin: 0 auto;   
            position: relative;
            background: #f8f3e8; 
            border: 2px dashed #333; 
        }

        .ticket-left {
            width: 65%;
            height: 100%;
            float: left;
            padding: 20px;
            box-sizing: border-box;
        }

        .ticket-right {
            width: 35%;
            height: 100%;
            float: right;
            padding: 20px;
            box-sizing: border-box;
            border-left: 2px dashed #333;
        }

        .movie-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .info-line {
            font-size: 16px;
            margin-bottom: 8px;
        }
        .info-line span {
            font-weight: bold;
        }

        .ticket-right .info-line {
            margin-bottom: 20px;
        }

        .circle-left, .circle-right {
            width: 20px;
            height: 20px;
            border: 2px solid #333;
            border-radius: 50%;
            background: #f8f3e8;
            position: absolute;
        }
        .circle-left {
            top: 50%;
            left: -11px; 
            transform: translateY(-50%);
        }
        .circle-right {
            top: 50%;
            right: -11px; 
            transform: translateY(-50%);
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="circle-left"></div>
        <div class="circle-right"></div>

        <div class="ticket-left">
            <div class="movie-title">
                {{ $ticketData['movie'] ?? 'Movie Title' }}
            </div>
            <div class="info-line">
                <span>Data:</span> {{ $ticketData['date'] ?? '----' }}
            </div>
            <div class="info-line">
                <p><strong>Hora:</strong> {{ isset($ticketData['time']) ? substr($ticketData['time'], 0, 5) : '----' }}</p>
            </div>
            @if(isset($ticketData['price']))
            <div class="info-line">
                <span>Preu:</span> {{ $ticketData['price'] }} €
            </div>
            @endif
            <div class="info-line">
                <span>Seient:</span>
                @if(isset($ticketData['seat']))
                    {{ $ticketData['seat']['row'] }}{{ $ticketData['seat']['number'] }}
                @else
                    ---
                @endif
            </div>
        </div>

        <div class="ticket-right">
            <div class="info-line">
                <span>Fila-Seient:</span>
                @if(isset($ticketData['seat']))
                    {{ $ticketData['seat']['row'] }}{{ $ticketData['seat']['number'] }}
                @else
                    ---
                @endif
            </div>
            <div class="info-line">
                <span>Ticket No:</span> {{ $ticketData['id'] }}
            </div>
        </div>
    </div>
</body>
</html>
