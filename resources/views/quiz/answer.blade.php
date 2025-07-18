<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Bandiere - Risposta</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
</head>
<body>
    <div class="container">
        <div class="progress-text">
            <span><strong>Progresso</strong></span>
            <span>Domanda {{ $questionNumber }} di {{ $totalQuestions }}</span>
        </div>
        
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ ($questionNumber / $totalQuestions) * 100 }}%"></div>
        </div>

        <div class="result-container">
            <div class="result-icon">
                @if($isCorrect)
                    🎉
                @else
                    ❌
                @endif
            </div>
            
            <h2 class="result-title {{ $isCorrect ? 'correct' : 'incorrect' }}">
                @if($isCorrect)
                    Corretto!
                @else
                    Sbagliato!
                @endif
            </h2>

            <div class="country-details">
                <h3>{{ $correctCountry->name }}</h3>
                <p><strong>Capitale:</strong> {{ $correctCountry->capital }}</p>
                <p><strong>Continente:</strong> {{ $correctCountry->continent }}</p>
                
                @if(!$isCorrect && $selectedAnswer)
                    <p style="color: #dc3545;"><strong>Hai selezionato:</strong> {{ $selectedAnswer->name }}</p>
                @endif
            </div>

            @if($questionNumber < $totalQuestions)
                <a href="{{ route('quiz.next') }}" class="next-btn">
                    ➡️ Prossima Domanda
                </a>
            @else
                <a href="{{ route('quiz.results') }}" class="next-btn">
                    🎯 Visualizza Risultati
                </a>
            @endif
        </div>
    </div>
</body>
</html>
