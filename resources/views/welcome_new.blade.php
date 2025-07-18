<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Quiz Bandiere del Mondo</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
        <style>
            .welcome-container {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: white;
                text-align: center;
                padding: 20px;
            }
            
            .welcome-header h1 {
                font-size: 3.5rem;
                margin-bottom: 1rem;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            }
            
            .welcome-header p {
                font-size: 1.3rem;
                margin-bottom: 3rem;
                opacity: 0.9;
            }
            
            .games-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 2rem;
                max-width: 1000px;
                width: 100%;
                margin-bottom: 2rem;
            }
            
            .game-card {
                background: rgba(255, 255, 255, 0.95);
                color: #333;
                border-radius: 15px;
                padding: 2rem;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                text-decoration: none;
            }
            
            .game-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 40px rgba(0,0,0,0.3);
                text-decoration: none;
                color: #333;
            }
            
            .game-icon {
                font-size: 4rem;
                margin-bottom: 1rem;
            }
            
            .game-title {
                font-size: 1.5rem;
                font-weight: 600;
                margin-bottom: 1rem;
            }
            
            .game-description {
                font-size: 1rem;
                opacity: 0.8;
                line-height: 1.5;
            }
            
            .auth-links {
                margin-top: 2rem;
            }
            
            .auth-links a {
                color: white;
                text-decoration: none;
                margin: 0 15px;
                padding: 10px 20px;
                border: 2px solid rgba(255,255,255,0.3);
                border-radius: 25px;
                transition: all 0.3s ease;
            }
            
            .auth-links a:hover {
                background: rgba(255,255,255,0.2);
                border-color: white;
            }
            
            @media (max-width: 768px) {
                .welcome-header h1 {
                    font-size: 2.5rem;
                }
                
                .games-grid {
                    grid-template-columns: 1fr;
                    gap: 1.5rem;
                }
                
                .game-card {
                    padding: 1.5rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="welcome-container">
            <div class="welcome-header">
                <h1>🌍 Quiz Bandiere del Mondo</h1>
                <p>Metti alla prova le tue conoscenze geografiche con i nostri giochi interattivi!</p>
            </div>
            
            <div class="games-grid">
                <a href="{{ route('quiz.index') }}" class="game-card">
                    <div class="game-icon">🎯</div>
                    <div class="game-title">Quiz Bandiere</div>
                    <div class="game-description">
                        Il classico quiz sulle bandiere del mondo. Scegli la difficoltà e sfida te stesso 
                        a riconoscere le bandiere di tutti i continenti.
                    </div>
                </a>
                
                <a href="{{ route('memory.index') }}" class="game-card">
                    <div class="game-icon">🧠</div>
                    <div class="game-title">Memory Game</div>
                    <div class="game-description">
                        Allena la tua memoria! Trova le coppie di bandiere nascoste nel minor tempo possibile. 
                        Personalizza la difficoltà scegliendo il numero di coppie.
                    </div>
                </a>
                
                <a href="{{ route('bonus.index') }}" class="game-card">
                    <div class="game-icon">🎯</div>
                    <div class="game-title">Gioco Bonus</div>
                    <div class="game-description">
                        Domande speciali su capitali, continenti e geografia. Sistema di punteggio avanzato 
                        con bonus per velocità e serie di risposte corrette.
                    </div>
                </a>
            </div>
            
            @if (Route::has('login'))
                <div class="auth-links">
                    @auth
                        <a href="{{ url('/dashboard') }}">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}">Accedi</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Registrati</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </body>
</html>
