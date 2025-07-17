<?php

namespace App\Services;

use App\Contracts\QuizServiceInterface;
use App\Contracts\CountryRepositoryInterface;
use App\Contracts\QuizScoreRepositoryInterface;
use App\Models\QuizScore;
use Carbon\Carbon;

class QuizService implements QuizServiceInterface
{
    protected $countryRepository;
    protected $quizScoreRepository;

    public function __construct(
        CountryRepositoryInterface $countryRepository,
        QuizScoreRepositoryInterface $quizScoreRepository
    ) {
        $this->countryRepository = $countryRepository;
        $this->quizScoreRepository = $quizScoreRepository;
    }

    public function startQuiz(int $difficulty, int $totalQuestions): array
    {
        return [
            'difficulty' => $difficulty,
            'total_questions' => $totalQuestions,
            'current_question' => 0,
            'score' => 0,
            'questions' => [],
            'answers' => [],
            'used_countries' => [],
            'start_time' => now()
        ];
    }

    public function generateQuestion(array $quiz): array
    {
        // Incrementa numero domanda
        $quiz['current_question']++;

        // Ottieni un paese casuale escludendo quelli già usati
        $countries = $this->countryRepository->getRandomForQuiz(
            $quiz['difficulty'],
            $quiz['used_countries'],
            1
        );

        if ($countries->isEmpty()) {
            // Se non ci sono più paesi, usa tutti i paesi disponibili
            $countries = $this->countryRepository->getRandomForQuiz(
                $quiz['difficulty'],
                [],
                1
            );
        }

        $country = $countries->first();

        // Aggiungi il paese alla lista di quelli usati
        $quiz['used_countries'][] = $country->id;

        // Genera risposte multiple (3 sbagliate + 1 corretta)
        $wrongAnswers = $this->countryRepository->getWrongAnswers(
            $country->id,
            $quiz['difficulty'],
            3
        );

        // Combina e mescola le risposte
        $allAnswers = $wrongAnswers->push($country)->shuffle();

        // Salva domanda nella sessione
        $quiz['questions'][$quiz['current_question']] = [
            'country' => $country,
            'answers' => $allAnswers,
            'correct_answer_id' => $country->id
        ];

        return [
            'quiz' => $quiz,
            'country' => $country,
            'answers' => $allAnswers
        ];
    }

    public function processAnswer(array $quiz, int $selectedAnswerId, int $correctAnswerId): array
    {
        $isCorrect = $selectedAnswerId == $correctAnswerId;

        if ($isCorrect) {
            $quiz['score']++;
        }

        // Salva la risposta
        $quiz['answers'][$quiz['current_question']] = [
            'selected_id' => $selectedAnswerId,
            'correct_id' => $correctAnswerId,
            'is_correct' => $isCorrect,
            'answered_at' => now()
        ];

        return [
            'quiz' => $quiz,
            'is_correct' => $isCorrect,
            'correct_country' => $this->countryRepository->findById($correctAnswerId),
            'selected_answer' => $this->countryRepository->findById($selectedAnswerId)
        ];
    }

    public function calculateResults(array $quiz): array
    {
        $score = $quiz['score'];
        $total = $quiz['total_questions'];
        $percentage = round(($score / $total) * 100);

        // Calcola tempo totale
        $totalTime = now()->diffInSeconds($quiz['start_time']);
        $avgTimePerQuestion = round($totalTime / $total);

        // Determina il messaggio in base alla performance
        [$message, $icon] = $this->getPerformanceMessage($percentage);

        return [
            'score' => $score,
            'total' => $total,
            'percentage' => $percentage,
            'total_time' => $totalTime,
            'avg_time_per_question' => $avgTimePerQuestion,
            'message' => $message,
            'icon' => $icon,
            'correct_answers' => $score,
            'wrong_answers' => $total - $score
        ];
    }

    public function saveScore(array $quiz, string $playerName, string $playerIp): QuizScore
    {
        $results = $this->calculateResults($quiz);

        return $this->quizScoreRepository->save([
            'player_name' => $playerName,
            'score' => $results['score'],
            'total_questions' => $results['total'],
            'percentage' => $results['percentage'],
            'difficulty_level' => $quiz['difficulty'],
            'total_time_seconds' => $results['total_time'],
            'avg_time_per_question' => $results['avg_time_per_question'],
            'quiz_details' => [
                'questions' => $quiz['questions'],
                'answers' => $quiz['answers'],
                'used_countries' => $quiz['used_countries']
            ],
            'player_ip' => $playerIp
        ]);
    }

    public function getQuizStats(): array
    {
        return $this->countryRepository->getStats();
    }

    /**
     * Determina il messaggio di performance in base alla percentuale
     */
    private function getPerformanceMessage(float $percentage): array
    {
        if ($percentage >= 90) {
            return ['🏆 Eccellente! Sei un vero esperto di geografia!', '🏆'];
        } elseif ($percentage >= 70) {
            return ['🥈 Molto bene! Conosci bene le bandiere del mondo!', '🥈'];
        } elseif ($percentage >= 50) {
            return ['🥉 Buon lavoro! Continua a studiare per migliorare!', '🥉'];
        } else {
            return ['📚 Non male per un primo tentativo! Esplora i paesi per imparare di più!', '📚'];
        }
    }
}
