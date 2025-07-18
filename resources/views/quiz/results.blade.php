<!DOCTYPE html>
<html lang="it"                <div class="stat-card">
                    <div class="stat-icon">⏱️</div>
                    <div class="stat-number">{{ $avg_time_per_question }}s</div>
                    <div class="stat-label">Tempo medio</div>
                </div>ead>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Bandiere - Risultati</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
</head>
<body>
    <div class="container">
        <div class="results-container">
            <div class="final-icon">{{ $icon }}</div>
            
            <h1>🎉 Quiz Completato!</h1>

            <div class="final-score">
                <div class="score-number">{{ $score }}/{{ $total }}</div>
                <div class="score-percentage">{{ $percentage }}%</div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">✅</div>
                    <div class="stat-number">{{ $correct_answers }}</div>
                    <div class="stat-label">Corrette</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">❌</div>
                    <div class="stat-number">{{ $wrong_answers }}</div>
                    <div class="stat-label">Sbagliate</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⏱️</div>
                    <div class="stat-number">{{ $avg_time_per_question }}s</div>
                    <div class="stat-label">Tempo Medio</div>
                </div>
            </div>

            <div class="performance-message">
                {{ $message }}
            </div>

            @if($showNameForm)
                <!-- Form per salvare il punteggio -->
                <div class="save-score-section">
                    <h3>💾 Salva il tuo punteggio!</h3>
                    <p>Inserisci il tuo nome per essere aggiunto alla classifica:</p>
                    
                    <form method="GET" action="{{ route('quiz.results') }}" class="save-score-form">
                        <div class="form-group">
                            <input type="text" 
                                   name="player_name" 
                                   placeholder="Il tuo nome..." 
                                   maxlength="50" 
                                   required 
                                   class="player-name-input">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            🏆 Salva Punteggio
                        </button>
                    </form>
                </div>
            @elseif($quizScore)
                <!-- Punteggio salvato -->
                <div class="score-saved-section">
                    <h3>✅ Punteggio Salvato!</h3>
                    <p>Complimenti <strong>{{ $quizScore->player_name }}</strong>!</p>
                    <p>La tua posizione: <strong>#{{ $quizScore->rank }}</strong> per il livello {{ $quizScore->difficulty_label }}</p>
                </div>
            @else
                <!-- Caso di fallback - mostra sempre il form se non ci sono altre condizioni -->
                <div class="save-score-section">
                    <h3>💾 Salva il tuo punteggio!</h3>
                    <p>Inserisci il tuo nome per essere aggiunto alla classifica:</p>
                    
                    <form method="GET" action="{{ route('quiz.results') }}" class="save-score-form">
                        <div class="form-group">
                            <input type="text" 
                                   name="player_name" 
                                   placeholder="Il tuo nome..." 
                                   maxlength="50" 
                                   required 
                                   class="player-name-input">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            🏆 Salva Punteggio
                        </button>
                    </form>
                </div>
            @endif

            <!-- Classifica Top 5 -->
            @if($topScores->count() > 0)
                <div class="leaderboard-preview">
                    <h3>🏆 Top 5 - Livello {{ $quiz['difficulty'] == 1 ? 'Facile' : ($quiz['difficulty'] == 2 ? 'Medio' : 'Difficile') }}</h3>
                    <div class="leaderboard-list">
                        @foreach($topScores as $index => $topScore)
                            <div class="leaderboard-item {{ $quizScore && $quizScore->id == $topScore->id ? 'current-player' : '' }}">
                                <div class="rank">{{ $index + 1 }}</div>
                                <div class="player-info">
                                    <div class="player-name">{{ $topScore->player_name }}</div>
                                    <div class="player-stats">{{ $topScore->percentage }}% - {{ $topScore->formatted_time }}</div>
                                </div>
                                <div class="badge">{{ $topScore->performance_badge }}</div>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('leaderboard.index', ['difficulty' => $quiz['difficulty']]) }}" class="btn btn-outline">
                        📊 Vedi Classifica Completa
                    </a>
                </div>
            @endif

            <div class="action-buttons">
                <a href="{{ route('quiz.index') }}" class="btn btn-success">
                    🔄 Gioca Ancora
                </a>
                <a href="{{ route('countries.index') }}" class="btn btn-secondary">
                    📚 Esplora Paesi
                </a>
            </div>
        </div>
    </div>
</body>
</html>
