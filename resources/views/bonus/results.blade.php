<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risultati Gioco Bonus</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bonus.css') }}">
</head>
<body>
    <div class="container">
        <!-- Header risultati -->
        <div class="results-header">
            <div class="rank-display">
                <div class="rank-icon" style="color: {{ $results['rank']['color'] }}">
                    {{ $results['rank']['icon'] }}
                </div>
                <div class="rank-info">
                    <h1>{{ $results['rank']['name'] }}</h1>
                    <p class="rank-subtitle">Congratulazioni per la tua performance!</p>
                </div>
            </div>
        </div>

        <!-- Statistiche principali -->
        <div class="main-stats">
            <div class="stat-card primary">
                <div class="stat-icon">🏆</div>
                <div class="stat-content">
                    <span class="stat-number">{{ $results['score'] }}</span>
                    <span class="stat-label">Punteggio Totale</span>
                </div>
            </div>

            <div class="stat-card success">
                <div class="stat-icon">✅</div>
                <div class="stat-content">
                    <span class="stat-number">{{ $results['correct_answers'] }}/{{ $results['total_questions'] }}</span>
                    <span class="stat-label">Risposte Corrette</span>
                </div>
            </div>

            <div class="stat-card info">
                <div class="stat-icon">🎯</div>
                <div class="stat-content">
                    <span class="stat-number">{{ $results['accuracy'] }}%</span>
                    <span class="stat-label">Accuratezza</span>
                </div>
            </div>

            <div class="stat-card warning">
                <div class="stat-icon">🔥</div>
                <div class="stat-content">
                    <span class="stat-number">{{ $results['max_streak'] }}</span>
                    <span class="stat-label">Streak Massimo</span>
                </div>
            </div>
        </div>

        <!-- Dettagli performance -->
        <div class="performance-details">
            <div class="detail-section">
                <h3>⏱️ Statistiche Tempo</h3>
                <div class="time-stats">
                    <div class="time-item">
                        <span class="time-label">Tempo Totale:</span>
                        <span class="time-value">{{ gmdate('i:s', $results['total_time']) }}</span>
                    </div>
                    <div class="time-item">
                        <span class="time-label">Tempo Medio per Domanda:</span>
                        <span class="time-value">{{ $results['average_time'] }}s</span>
                    </div>
                    <div class="time-item">
                        <span class="time-label">Difficoltà:</span>
                        <span class="difficulty-badge difficulty-{{ $results['difficulty'] }}">
                            @switch($results['difficulty'])
                                @case('easy') Facile @break
                                @case('medium') Medio @break
                                @case('hard') Difficile @break
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <h3>📊 Ripartizione Risposte</h3>
                <div class="answer-breakdown">
                    <div class="answer-chart">
                        <div class="chart-bar correct" 
                             style="width: {{ ($results['correct_answers'] / $results['total_questions']) * 100 }}%">
                            <span>{{ $results['correct_answers'] }} Corrette</span>
                        </div>
                        <div class="chart-bar wrong" 
                             style="width: {{ ($results['wrong_answers'] / $results['total_questions']) * 100 }}%">
                            <span>{{ $results['wrong_answers'] }} Sbagliate</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cronologia risposte -->
        <div class="answers-history">
            <h3>📜 Cronologia Risposte</h3>
            <div class="history-container">
                @foreach($results['answers_history'] as $index => $answer)
                    <div class="history-item {{ $answer['is_correct'] ? 'correct' : 'wrong' }}">
                        <div class="history-header">
                            <span class="question-number">Q{{ $index + 1 }}</span>
                            <span class="question-type">
                                @switch($answer['question']['type'])
                                    @case('capital') 🏛️ Capitale @break
                                    @case('continent') 🌍 Continente @break
                                    @case('true_false') ❓ Vero/Falso @break
                                    @case('complete_name') ✏️ Completa @break
                                @endswitch
                            </span>
                            <span class="result-icon">
                                {{ $answer['is_correct'] ? '✅' : '❌' }}
                            </span>
                        </div>
                        
                        <div class="history-content">
                            <div class="question-preview">
                                {{ $answer['question']['question'] }}
                            </div>
                            
                            <div class="answer-details">
                                <div class="user-answer">
                                    <strong>La tua risposta:</strong> {{ $answer['user_answer'] }}
                                </div>
                                @if(!$answer['is_correct'])
                                    <div class="correct-answer">
                                        <strong>Risposta corretta:</strong> {{ $answer['correct_answer'] }}
                                    </div>
                                @endif
                                @if($answer['is_correct'])
                                    <div class="points-earned">
                                        <strong>Punti guadagnati:</strong> {{ $answer['points_earned'] }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Azioni finali -->
        <div class="final-actions">
            <div class="action-group">
                <button class="btn btn-primary" onclick="window.location.href='{{ route('bonus.index') }}'">
                    🎯 Nuovo Gioco Bonus
                </button>
                <button class="btn btn-success" onclick="window.location.href='{{ route('quiz.index') }}'">
                    🌍 Quiz Bandiere
                </button>
                <button class="btn btn-info" onclick="window.location.href='{{ route('memory.index') }}'">
                    🧠 Memory Game
                </button>
            </div>
        </div>

        <!-- Consigli per migliorare -->
        <div class="improvement-tips">
            <h3>💡 Consigli per Migliorare</h3>
            <div class="tips-container">
                @if($results['accuracy'] < 60)
                    <div class="tip-item">
                        <span class="tip-icon">📚</span>
                        <span>Studia le capitali e i continenti più famosi</span>
                    </div>
                    <div class="tip-item">
                        <span class="tip-icon">🗺️</span>
                        <span>Esplora un atlante geografico online</span>
                    </div>
                @elseif($results['accuracy'] < 80)
                    <div class="tip-item">
                        <span class="tip-icon">🎯</span>
                        <span>Prova la difficoltà più alta per più punti</span>
                    </div>
                    <div class="tip-item">
                        <span class="tip-icon">⚡</span>
                        <span>Cerca di rispondere più velocemente per il bonus tempo</span>
                    </div>
                @else
                    <div class="tip-item">
                        <span class="tip-icon">🏆</span>
                        <span>Fantastico! Prova a mantenere streak più lunghi</span>
                    </div>
                    <div class="tip-item">
                        <span class="tip-icon">🌟</span>
                        <span>Sfida te stesso con 20 domande difficili!</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

</body>
</html>
