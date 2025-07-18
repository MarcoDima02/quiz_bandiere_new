<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gioco Bonus - In Corso</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bonus.css') }}">
</head>
<body>
    <div class="container">
        <!-- Header con statistiche -->
        <div class="bonus-game-header">
            <div class="game-progress">
                <h2>🎯 Gioco Bonus</h2>
                <div class="progress-info">
                    <span class="question-counter">
                        Domanda {{ $question['question_number'] }} di {{ $question['total_questions'] }}
                    </span>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $question['progress'] }}%"></div>
                    </div>
                </div>
            </div>
            
            <div class="game-stats">
                <div class="stat-box">
                    <span class="stat-value">{{ $stats['score'] }}</span>
                    <span class="stat-label">Punteggio</span>
                </div>
                <div class="stat-box">
                    <span class="stat-value">{{ $stats['correct_answers'] }}</span>
                    <span class="stat-label">Corrette</span>
                </div>
                <div class="stat-box">
                    <span class="stat-value">{{ $stats['streak'] }}</span>
                    <span class="stat-label">Streak</span>
                </div>
                <div class="stat-box">
                    <span class="stat-value" id="timer">00:00</span>
                    <span class="stat-label">Tempo</span>
                </div>
            </div>
        </div>

        <!-- Area della domanda -->
        <div class="question-container">
            <div class="question-type-badge">
                @switch($question['type'])
                    @case('capital')
                        <span class="type-icon">🏛️</span>
                        <span>Capitale</span>
                        @break
                    @case('continent')
                        <span class="type-icon">🌍</span>
                        <span>Continente</span>
                        @break
                    @case('true_false')
                        <span class="type-icon">❓</span>
                        <span>Vero o Falso</span>
                        @break
                    @case('complete_name')
                        <span class="type-icon">✏️</span>
                        <span>Completa Nome</span>
                        @break
                @endswitch
            </div>

            <div class="question-content">
                @if($question['type'] !== 'true_false' && isset($question['country']))
                    <div class="country-flag">
                        <img src="{{ $question['country']->flag_url }}" 
                             alt="Bandiera di {{ $question['country']->name }}"
                             class="flag-image">
                    </div>
                @endif

                <h2 class="question-text">{{ $question['question'] }}</h2>

                @if($question['difficulty'])
                    <div class="difficulty-indicator">
                        <span class="difficulty-badge difficulty-{{ $question['difficulty'] }}">
                            @switch($question['difficulty'])
                                @case(1) Facile @break
                                @case(2) Medio @break
                                @case(3) Difficile @break
                            @endswitch
                        </span>
                        <span class="points-info">+{{ $question['points_base'] }} punti base</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Risposte -->
        <div class="answers-container">
            @foreach($question['options'] as $index => $option)
                <button class="answer-btn" 
                        data-answer="{{ $option }}"
                        data-index="{{ $index }}">
                    <span class="answer-letter">{{ chr(65 + $index) }}</span>
                    <span class="answer-text">{{ $option }}</span>
                </button>
            @endforeach
        </div>

        <!-- Feedback area (nascosta inizialmente) -->
        <div class="feedback-container" id="feedback-container" style="display: none;">
            <div class="feedback-content">
                <div class="feedback-result">
                    <span class="result-icon" id="result-icon"></span>
                    <span class="result-text" id="result-text"></span>
                </div>
                <div class="feedback-details">
                    <div class="correct-answer" id="correct-answer"></div>
                    <div class="explanation" id="explanation"></div>
                    <div class="points-earned" id="points-earned"></div>
                </div>
                <button class="btn btn-primary" id="next-question-btn">
                    ➡️ Prossima Domanda
                </button>
            </div>
        </div>

        <!-- Pulsanti di controllo -->
        <div class="game-controls">
            <button class="btn btn-secondary" id="quit-game">
                🏠 Abbandona
            </button>
            <div class="bonus-info-toggle">
                <button class="btn btn-outline" id="show-bonus-info">
                    💡 Info Bonus
                </button>
            </div>
        </div>

        <!-- Modal info bonus -->
        <div class="modal" id="bonus-info-modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>🏆 Sistema di Punteggio Bonus</h3>
                    <button class="modal-close" id="close-bonus-info">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="bonus-explanation">
                        <h4>📊 Calcolo Punti:</h4>
                        <ul>
                            <li><strong>Punti Base:</strong> Variabili per tipo domanda (50-125)</li>
                            <li><strong>Moltiplicatore Difficoltà:</strong> 
                                <span class="difficulty-easy">Facile: x1.0</span> | 
                                <span class="difficulty-medium">Medio: x1.5</span> | 
                                <span class="difficulty-hard">Difficile: x2.0</span>
                            </li>
                            <li><strong>Bonus Streak:</strong> +10% per ogni risposta consecutiva (max 100%)</li>
                            <li><strong>Bonus Tempo:</strong> +50% per risposte entro 10 secondi</li>
                        </ul>
                        
                        <h4>🏅 Ranking:</h4>
                        <div class="rank-list">
                            <div class="rank-item">🏆 Genio della Geografia (90%+ accuratezza, 1000+ punti, 5+ streak)</div>
                            <div class="rank-item">🥇 Esperto Mondiale (80%+ accuratezza, 750+ punti)</div>
                            <div class="rank-item">🥈 Viaggiatore Esperto (70%+ accuratezza, 500+ punti)</div>
                            <div class="rank-item">🗺️ Esploratore (60%+ accuratezza)</div>
                            <div class="rank-item">🧭 Novizio</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Particelle per effetti -->
        <div class="particles" id="particles"></div>
    </div>

    <script src="{{ asset('js/bonus.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configura URL per le chiamate AJAX
            document.body.dataset.answerUrl = '{{ route("bonus.answer") }}';
            document.body.dataset.quitUrl = '{{ route("bonus.quit") }}';
            document.body.dataset.resultsUrl = '{{ route("bonus.results") }}';
            
            // Inizializza il timer per la domanda corrente
            window.questionStartTime = new Date();
        });
    </script>
</body>
</html>
