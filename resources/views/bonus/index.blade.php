<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gioco Bonus - Domande Speciali</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bonus.css') }}">
</head>
<body>
    <div class="container">
        <div class="bonus-header">
            <h1>🎯 Gioco Bonus</h1>
            <p class="subtitle">Domande speciali per veri esperti di geografia!</p>
        </div>

        <div class="bonus-features">
            <div class="feature-card">
                <div class="feature-icon">🏛️</div>
                <h3>Capitali del Mondo</h3>
                <p>Indovina le capitali di paesi da tutto il mondo</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🌍</div>
                <h3>Quiz Continenti</h3>
                <p>Scopri in quale continente si trovano i paesi</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">❓</div>
                <h3>Vero o Falso</h3>
                <p>Metti alla prova le tue conoscenze geografiche</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">✏️</div>
                <h3>Completa il Nome</h3>
                <p>Completa i nomi dei paesi mancanti</p>
            </div>
        </div>

        <div class="game-config">
            <h2>🎮 Configura il tuo gioco</h2>
            
            <form id="bonus-config-form">
                <div class="config-group">
                    <label for="difficulty">📊 Difficoltà:</label>
                    <div class="difficulty-options">
                        <input type="radio" id="easy" name="difficulty" value="easy">
                        <label for="easy" class="difficulty-label easy">
                            <span class="difficulty-icon">🟢</span>
                            <div>
                                <strong>Facile</strong>
                                <small>Paesi famosi, +1.0x punti</small>
                            </div>
                        </label>

                        <input type="radio" id="medium" name="difficulty" value="medium" checked>
                        <label for="medium" class="difficulty-label medium">
                            <span class="difficulty-icon">🟡</span>
                            <div>
                                <strong>Medio</strong>
                                <small>Mix di paesi, +1.5x punti</small>
                            </div>
                        </label>

                        <input type="radio" id="hard" name="difficulty" value="hard">
                        <label for="hard" class="difficulty-label hard">
                            <span class="difficulty-icon">🔴</span>
                            <div>
                                <strong>Difficile</strong>
                                <small>Paesi oscuri, +2.0x punti</small>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="config-group">
                    <label for="question_count">🔢 Numero di domande:</label>
                    <div class="question-count-options">
                        <input type="radio" id="q5" name="question_count" value="5">
                        <label for="q5" class="count-label">
                            <span>5</span>
                            <small>Veloce</small>
                        </label>

                        <input type="radio" id="q10" name="question_count" value="10" checked>
                        <label for="q10" class="count-label">
                            <span>10</span>
                            <small>Standard</small>
                        </label>

                        <input type="radio" id="q15" name="question_count" value="15">
                        <label for="q15" class="count-label">
                            <span>15</span>
                            <small>Lungo</small>
                        </label>

                        <input type="radio" id="q20" name="question_count" value="20">
                        <label for="q20" class="count-label">
                            <span>20</span>
                            <small>Epico</small>
                        </label>
                    </div>
                </div>

                <div class="bonus-info">
                    <h3>🏆 Sistema di Punteggio</h3>
                    <ul>
                        <li><strong>Punti Base:</strong> Variabili per tipo di domanda</li>
                        <li><strong>Moltiplicatore Difficoltà:</strong> Bonus per difficoltà scelta</li>
                        <li><strong>Bonus Streak:</strong> +10% per ogni risposta consecutiva corretta</li>
                        <li><strong>Bonus Tempo:</strong> +50% per risposte veloci (entro 10 secondi)</li>
                        <li><strong>Ranking:</strong> Dal Novizio al Genio della Geografia!</li>
                    </ul>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">
                        🚀 Inizia Gioco Bonus
                    </button>
                    <a href="{{ route('quiz.index') }}" class="btn btn-secondary">
                        🏠 Menu Principale
                    </a>
                </div>
            </form>
        </div>

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <script src="{{ asset('js/bonus.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configura URL per le chiamate AJAX
            document.body.dataset.startUrl = '{{ route("bonus.start") }}';
            document.body.dataset.gameUrl = '{{ route("bonus.game") }}';
        });
    </script>
</body>
</html>
