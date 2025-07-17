<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classifica Quiz Bandiere</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
</head>
<body>
    <div class="container">
        <div class="text-center">
            <h1>🏆 Classifica Quiz Bandiere</h1>
            <p>I migliori punteggi di tutti i tempi</p>
        </div>

        <!-- Filtri -->
        <div class="filters-section">
            <div class="form-group">
                <h3>🎯 Filtri</h3>
                <div class="filters-grid">
                    <div>
                        <label><strong>Difficoltà:</strong></label>
                        <select id="difficulty-filter" onchange="applyFilters()">
                            <option value="1" {{ $difficulty == 1 ? 'selected' : '' }}>⭐ Facile</option>
                            <option value="2" {{ $difficulty == 2 ? 'selected' : '' }}>⭐⭐ Medio</option>
                            <option value="3" {{ $difficulty == 3 ? 'selected' : '' }}>⭐⭐⭐ Difficile</option>
                        </select>
                    </div>
                    <div>
                        <label><strong>Periodo:</strong></label>
                        <select id="period-filter" onchange="applyFilters()">
                            <option value="all" {{ $period == 'all' ? 'selected' : '' }}>Tutti i tempi</option>
                            <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Ultima settimana</option>
                            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Ultimo mese</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiche -->
        <div class="stats-grid" style="margin-bottom: 30px;">
            <div class="stat-card">
                <div class="stat-icon">🎮</div>
                <div class="stat-number">{{ $stats['total_games'] }}</div>
                <div class="stat-label">Partite Totali</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-number">{{ $stats['total_players'] }}</div>
                <div class="stat-label">Giocatori Unici</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-number">{{ round($stats['avg_percentage'], 1) }}%</div>
                <div class="stat-label">Media Punteggi</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⚡</div>
                <div class="stat-number">{{ gmdate('i:s', $stats['best_time']) }}</div>
                <div class="stat-label">Miglior Tempo</div>
            </div>
        </div>

        <!-- Classifica -->
        <div class="leaderboard-section">
            <h2>🥇 Classifica - Livello {{ $difficulty == 1 ? 'Facile' : ($difficulty == 2 ? 'Medio' : 'Difficile') }}</h2>
            
            @if($topScores->count() > 0)
                <div class="leaderboard-table">
                    <div class="leaderboard-header">
                        <div class="rank-col">Pos</div>
                        <div class="player-col">Giocatore</div>
                        <div class="score-col">Punteggio</div>
                        <div class="time-col">Tempo</div>
                        <div class="date-col">Data</div>
                        <div class="badge-col">Badge</div>
                    </div>
                    
                    @foreach($topScores as $index => $score)
                        <div class="leaderboard-row {{ $index < 3 ? 'podium-' . ($index + 1) : '' }}">
                            <div class="rank-col">
                                @if($index == 0) 🥇
                                @elseif($index == 1) 🥈
                                @elseif($index == 2) 🥉
                                @else {{ $index + 1 }}
                                @endif
                            </div>
                            <div class="player-col">
                                <strong>{{ $score->player_name }}</strong>
                            </div>
                            <div class="score-col">
                                <div class="score-main">{{ $score->score }}/{{ $score->total_questions }}</div>
                                <div class="score-percentage">{{ $score->percentage }}%</div>
                            </div>
                            <div class="time-col">{{ $score->formatted_time }}</div>
                            <div class="date-col">{{ $score->created_at->format('d/m/Y') }}</div>
                            <div class="badge-col">{{ $score->performance_badge }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-scores">
                    <p>📝 Nessun punteggio ancora registrato per questo livello.</p>
                    <p>Sii il primo a giocare!</p>
                </div>
            @endif
        </div>

        <!-- Azioni -->
        <div class="action-buttons">
            <a href="{{ route('quiz.index') }}" class="btn btn-success">
                🎯 Gioca Ora
            </a>
            <a href="{{ route('countries.index') }}" class="btn btn-secondary">
                📚 Esplora Paesi
            </a>
        </div>
    </div>

    <script>
        function applyFilters() {
            const difficulty = document.getElementById('difficulty-filter').value;
            const period = document.getElementById('period-filter').value;
            
            window.location.href = `{{ route('leaderboard.index') }}?difficulty=${difficulty}&period=${period}`;
        }
    </script>
</body>
</html>
