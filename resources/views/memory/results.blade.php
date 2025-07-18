<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game - Risultati</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
    <link rel="stylesheet" href="{{ asset('css/memory.css') }}">
    <style>
        .memory-results {
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .results-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        
        .results-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #2c3e50;
        }
        
        .performance-message {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
        }
        
        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }
        
        .result-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-left: 4px solid #3498db;
        }
        
        .result-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .result-number {
            font-size: 2rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        .result-label {
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        .difficulty-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            margin: 1rem 0;
        }
        
        .difficulty-1 {
            background: #d4edda;
            color: #155724;
        }
        
        .difficulty-2 {
            background: #fff3cd;
            color: #856404;
        }
        
        .difficulty-3 {
            background: #f8d7da;
            color: #721c24;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }
        
        .btn {
            padding: 1rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #3498db;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2980b9;
        }
        
        .btn-success {
            background: #27ae60;
            color: white;
        }
        
        .btn-success:hover {
            background: #229954;
        }
        
        .btn-secondary {
            background: #95a5a6;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="memory-results">
            <div class="results-icon">{{ $results['icon'] }}</div>
            
            <h1 class="results-title">🎉 Memory Completato!</h1>
            
            <div class="performance-message">
                {{ $results['message'] }}
            </div>

            <div class="difficulty-badge difficulty-{{ $results['difficulty'] }}">
                Livello: {{ $results['difficulty'] == 1 ? '⭐ Facile' : ($results['difficulty'] == 2 ? '⭐⭐ Medio' : '⭐⭐⭐ Difficile') }}
            </div>

            <div class="results-grid">
                <div class="result-card">
                    <div class="result-icon">🎯</div>
                    <div class="result-number">{{ $results['matched_pairs'] }}/{{ $results['total_pairs'] }}</div>
                    <div class="result-label">Coppie Trovate</div>
                </div>
                
                <div class="result-card">
                    <div class="result-icon">🎮</div>
                    <div class="result-number">{{ $results['moves'] }}</div>
                    <div class="result-label">Mosse Totali</div>
                </div>
                
                <div class="result-card">
                    <div class="result-icon">⚡</div>
                    <div class="result-number">{{ $results['efficiency'] }}%</div>
                    <div class="result-label">Efficienza</div>
                </div>
                
                <div class="result-card">
                    <div class="result-icon">⏱️</div>
                    <div class="result-number">
                        @php
                            $minutes = floor($results['total_time'] / 60);
                            $seconds = $results['total_time'] % 60;
                        @endphp
                        {{ $minutes }}:{{ str_pad($seconds, 2, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="result-label">Tempo Totale</div>
                </div>
                
                <div class="result-card">
                    <div class="result-icon">🏆</div>
                    <div class="result-number">{{ number_format($results['score']) }}</div>
                    <div class="result-label">Punteggio</div>
                </div>
                
                <div class="result-card">
                    <div class="result-icon">🎲</div>
                    <div class="result-number">{{ round($results['total_pairs'] / max(1, $results['moves'] / 2), 1) }}</div>
                    <div class="result-label">Precisione</div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ route('memory.index') }}" class="btn btn-primary">
                    🔄 Gioca Ancora
                </a>
                <a href="{{ route('quiz.index') }}" class="btn btn-success">
                    🌍 Quiz Bandiere
                </a>
                <a href="{{ route('countries.index') }}" class="btn btn-secondary">
                    📚 Esplora Paesi
                </a>
            </div>

            <!-- Dettagli Performance -->
            <div style="margin-top: 3rem; padding: 2rem; background: #f8f9fa; border-radius: 12px;">
                <h3>📈 Analisi Performance</h3>
                <div style="text-align: left; margin-top: 1rem;">
                    <p><strong>💡 Suggerimenti:</strong></p>
                    <ul style="list-style: none; padding: 0;">
                        @if($results['efficiency'] < 50)
                            <li>🎯 Cerca di memorizzare meglio la posizione delle carte</li>
                            <li>🧠 Prova a creare schemi mentali per ricordare</li>
                        @elseif($results['efficiency'] < 80)
                            <li>⚡ Ottima memoria! Prova a essere più veloce</li>
                            <li>🎪 Aumenta la difficoltà per una sfida maggiore</li>
                        @else
                            <li>🏆 Incredibile! Hai una memoria eccezionale!</li>
                            <li>🌟 Prova il livello più difficile con più carte</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
