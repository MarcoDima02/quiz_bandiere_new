<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game - Bandiere del Mondo</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
    <link rel="stylesheet" href="{{ asset('css/memory.css') }}">
    <style>
        .memory-intro {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .memory-intro h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #2c3e50;
        }
        
        .memory-intro p {
            font-size: 1.2rem;
            color: #7f8c8d;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .game-rules {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin: 2rem 0;
            border-left: 4px solid #3498db;
        }
        
        .game-rules h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        
        .game-rules ul {
            list-style: none;
            padding: 0;
        }
        
        .game-rules li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .game-rules li:last-child {
            border-bottom: none;
        }
        
        .game-rules li::before {
            content: "✨ ";
            color: #3498db;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="memory-intro">
            <h1>🧠 Memory Game</h1>
            <p>Metti alla prova la tua memoria con le bandiere del mondo! Trova tutte le coppie nel minor numero di mosse possibili.</p>
        </div>

        <div class="game-rules">
            <h3>📋 Come Giocare</h3>
            <ul>
                <li>Clicca su due carte per girarle</li>
                <li>Se le bandiere sono uguali, rimangono scoperte</li>
                <li>Se sono diverse, si rigirano automaticamente</li>
                <li>Trova tutte le coppie per vincere!</li>
                <li>Meno mosse fai, più punti ottieni</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('memory.start') }}">
            @csrf
            
            <!-- Difficulty Selection -->
            <div class="form-group">
                <h3>🎯 Seleziona Difficoltà</h3>
                <div class="options-grid">
                    <label class="option-card selected">
                        <input type="radio" name="difficulty" value="1" checked>
                        <div>⭐ Facile</div>
                        <small>Paesi più conosciuti</small>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="difficulty" value="2">
                        <div>⭐⭐ Medio</div>
                        <small>Include paesi medi</small>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="difficulty" value="3">
                        <div>⭐⭐⭐ Difficile</div>
                        <small>Tutti i paesi del mondo</small>
                    </label>
                </div>
            </div>

            <!-- Number of Pairs -->
            <div class="form-group">
                <h3>🎴 Numero di Coppie</h3>
                <div class="options-grid">
                    <label class="option-card">
                        <input type="radio" name="pairs" value="6">
                        <div>6 Coppie</div>
                        <small>Facile (12 carte)</small>
                    </label>
                    <label class="option-card selected">
                        <input type="radio" name="pairs" value="8" checked>
                        <div>8 Coppie</div>
                        <small>Normale (16 carte)</small>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="pairs" value="10">
                        <div>10 Coppie</div>
                        <small>Difficile (20 carte)</small>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="pairs" value="12">
                        <div>12 Coppie</div>
                        <small>Estremo (24 carte)</small>
                    </label>
                </div>
            </div>

            <button type="submit" class="start-button">
                🧠 Inizia Memory Game
            </button>
        </form>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="{{ route('quiz.index') }}" class="btn">🌍 Quiz Bandiere</a>
            <a href="{{ route('countries.index') }}" class="btn">📚 Esplora Paesi</a>
            <a href="{{ route('leaderboard.index') }}" class="btn">🏆 Classifica</a>
            <a href="{{ route('bonus.index') }}" class="btn">🎯 Gioco Bonus</a>
        </div>
    </div>

    <script src="{{ asset('js/quiz.js') }}"></script>
</body>
</html>
