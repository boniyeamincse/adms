<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Admit Cards</title>
    <style>
        .page-break { page-break-after: always; }
        .admit-card {
            border: 2px solid #2c3e50;
            margin: 10px;
            page-break-inside: avoid;
        }
        .header {
            background: #2c3e50;
            color: white;
            padding: 10px;
            text-align: center;
            border-bottom: 2px solid #e74c3c;
        }
    </style>
</head>
<body>
    @foreach($admit_cards->chunk(2) as $cardChunk)
        @foreach($cardChunk as $admit_card)
            <div class="admit-card">
                @include('admit-cards.pdf-content', ['admit_card' => $admit_card])
            </div>
            @if(!$loop->last || $cardChunk->count() == 2)
                <div class="page-break"></div>
            @endif
        @endforeach
    @endforeach
</body>
</html>