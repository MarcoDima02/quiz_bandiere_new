<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Bandiere - Domanda {{ $questionNumber }}</title>
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

        <div class="question-container">
            <h1>🌍 Quiz Bandiere del Mondo</h1>
            
            <img src="{{ $country->flag_url }}" alt="Bandiera" class="flag-image">
            
            <h2>Di quale paese è questa bandiera?</h2>
            
            
        </div>

        <form method="POST" action="{{ route('quiz.answer') }}">
            @csrf
            <div class="answers-grid">
                @foreach($answers as $answer)
                    <button type="submit" name="answer_id" value="{{ $answer->id }}" class="answer-btn">
                        {{ $answer->name }}
                    </button>
                @endforeach
            </div>
        </form>
    </div>
</body>
</html>
