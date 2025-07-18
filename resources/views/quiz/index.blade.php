<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Bandiere del Mondo</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
</head>
<body>
    <div class="container">
        <div class="text-center mb-4">
            <h1>🌍 Quiz Bandiere del Mondo</h1>
            <p>Metti alla prova la tua conoscenza delle bandiere internazionali!</p>
        </div>

        <form method="POST" action="{{ route('quiz.start') }}">
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

            <!-- Questions Count -->
            <div class="form-group">
                <h3>📝 Numero di Domande</h3>
                <div class="options-grid">
                    <label class="option-card">
                        <input type="radio" name="questions" value="5">
                        <div>5 Domande</div>
                        <small>Veloce</small>
                    </label>
                    <label class="option-card selected">
                        <input type="radio" name="questions" value="10" checked>
                        <div>10 Domande</div>
                        <small>Standard</small>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="questions" value="15">
                        <div>15 Domande</div>
                        <small>Esteso</small>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="questions" value="20">
                        <div>20 Domande</div>
                        <small>Completo</small>
                    </label>
                </div>
            </div>

            <button type="submit" class="start-button">
                🚀 Inizia il Quiz
            </button>
        </form>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="{{ route('countries.index') }}" class="btn">📚 Esplora Paesi</a>
            <a href="{{ route('leaderboard.index') }}" class="btn">🏆 Classifica</a>
            <a href="{{ route('memory.index') }}" class="btn">🧠 Memory Game</a>
            <a href="{{ route('bonus.index') }}" class="btn">🎯 Gioco Bonus</a>
        </div>
    </div>

    <script src="{{ asset('js/quiz.js') }}"></script>
</body>
</html>
