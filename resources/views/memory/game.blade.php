<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game - In Gioco</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
    <link rel="stylesheet" href="{{ asset('css/memory.css') }}">
</head>
<body>
    <div class="container">
        <div class="memory-header game-view">
            <h1>🧠 Memory Game</h1>
            <div class="game-stats">
                <div class="stat-item">
                    <div class="stat-number" id="moves-count">{{ $game['moves'] }}</div>
                    <div class="stat-label">Mosse</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="pairs-found">{{ $game['matched_pairs'] }}</div>
                    <div class="stat-label">Coppie</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="score">{{ $game['score'] }}</div>
                    <div class="stat-label">Punteggio</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="timer">00:00</div>
                    <div class="stat-label">Tempo</div>
                </div>
            </div>
        </div>

        <div class="memory-grid pairs-{{ $game['total_pairs'] }}" id="memory-grid">
            @foreach($game['cards'] as $index => $card)
                <div class="memory-card {{ $card['is_flipped'] ? 'flipped' : '' }} {{ $card['is_matched'] ? 'matched' : '' }}" 
                     data-index="{{ $index }}" 
                     data-country-id="{{ $card['id'] }}">
                    <div class="card-inner">
                        <div class="card-front">
                            🌍
                        </div>
                        <div class="card-back">
                            <img src="{{ $card['flag_url'] }}" alt="{{ $card['country']->name }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($game['is_complete'])
            <div class="game-complete">
                <h2>🎉 Complimenti!</h2>
                <p>Hai completato il Memory Game!</p>
                <a href="{{ route('memory.results') }}" class="btn btn-light">
                    📊 Visualizza Risultati
                </a>
            </div>
        @endif

        <div class="action-buttons">
            <a href="{{ route('memory.index') }}" class="btn btn-secondary">
                🏠 Menu Principale
            </a>
            <button onclick="restartGame()" class="btn btn-outline">
                🔄 Ricomincia
            </button>
        </div>
    </div>

    <script src="{{ asset('js/memory.js') }}"></script>
    <script>
        // Configurazione specifica per questa pagina
        document.addEventListener('DOMContentLoaded', function() {
            // Configura URL per le chiamate AJAX
            document.body.dataset.flipUrl = '{{ route("memory.flip") }}';
            document.body.dataset.resultsUrl = '{{ route("memory.results") }}';
        });
    </script>
</body>
</html>
