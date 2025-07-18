<?php

namespace App\Services;

use App\Contracts\BonusGameServiceInterface;
use App\Contracts\CountryRepositoryInterface;
use Illuminate\Support\Collection;

class BonusGameService implements BonusGameServiceInterface
{
    protected CountryRepositoryInterface $countryRepository;
    
    public function __construct(CountryRepositoryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function startBonusGame(string $difficulty = 'medium', int $questionCount = 10): array
    {
        $difficultyLevel = $this->convertDifficultyToLevel($difficulty);
        $countries = $this->countryRepository->getByDifficulty($difficultyLevel);
        $selectedCountries = $countries->random(min($questionCount, $countries->count()));
        
        $questions = [];
        foreach ($selectedCountries as $country) {
            $questions[] = $this->generateRandomQuestion($country);
        }
        
        $gameData = [
            'questions' => $questions,
            'current_question' => 0,
            'score' => 0,
            'correct_answers' => 0,
            'wrong_answers' => 0,
            'difficulty' => $difficulty,
            'total_questions' => count($questions),
            'start_time' => now(),
            'bonus_multiplier' => $this->getBonusMultiplier($difficulty),
            'streak' => 0,
            'max_streak' => 0,
            'time_bonus' => true,
            'answers_history' => []
        ];
        
        session(['bonus_game' => $gameData]);
        
        return $gameData;
    }

    public function getCurrentBonusQuestion(): ?array
    {
        $game = session('bonus_game');
        
        if (!$game || $this->isBonusGameComplete()) {
            return null;
        }
        
        $currentIndex = $game['current_question'];
        $question = $game['questions'][$currentIndex] ?? null;
        
        if ($question) {
            $question['question_number'] = $currentIndex + 1;
            $question['total_questions'] = $game['total_questions'];
            $question['progress'] = round(($currentIndex / $game['total_questions']) * 100);
        }
        
        return $question;
    }

    public function processBonusAnswer(string $answer): array
    {
        $game = session('bonus_game');
        $currentQuestion = $this->getCurrentBonusQuestion();
        
        if (!$game || !$currentQuestion) {
            return ['error' => 'Nessuna domanda attiva'];
        }
        
        $isCorrect = $this->checkAnswer($answer, $currentQuestion);
        $points = 0;
        
        if ($isCorrect) {
            $game['correct_answers']++;
            $game['streak']++;
            $game['max_streak'] = max($game['max_streak'], $game['streak']);
            
            // Calcola punti base
            $basePoints = $this->getBasePoints($currentQuestion['type']);
            
            // Bonus per difficoltà
            $difficultyBonus = $game['bonus_multiplier'];
            
            // Bonus per streak
            $streakBonus = min($game['streak'] * 0.1, 1.0); // Max 100% bonus
            
            // Bonus per tempo (se risposta veloce)
            $timeBonus = $this->calculateTimeBonus($currentQuestion);
            
            $points = round($basePoints * $difficultyBonus * (1 + $streakBonus + $timeBonus));
            $game['score'] += $points;
        } else {
            $game['wrong_answers']++;
            $game['streak'] = 0;
        }
        
        // Salva la risposta nella cronologia
        $game['answers_history'][] = [
            'question' => $currentQuestion,
            'user_answer' => $answer,
            'correct_answer' => $currentQuestion['correct_answer'],
            'is_correct' => $isCorrect,
            'points_earned' => $points,
            'answered_at' => now()
        ];
        
        // Passa alla domanda successiva
        $game['current_question']++;
        
        session(['bonus_game' => $game]);
        
        return [
            'is_correct' => $isCorrect,
            'correct_answer' => $currentQuestion['correct_answer'],
            'points_earned' => $points,
            'explanation' => $this->getExplanation($currentQuestion),
            'streak' => $game['streak'],
            'total_score' => $game['score'],
            'is_complete' => $this->isBonusGameComplete()
        ];
    }

    public function generateCapitalQuestion($country): array
    {
        $wrongCapitals = $this->countryRepository->getRandomCapitals(3, [$country->id]);
        $options = $wrongCapitals->pluck('capital')->toArray();
        $options[] = $country->capital;
        shuffle($options);
        
        return [
            'type' => 'capital',
            'question' => "Qual è la capitale di {$country->name}?",
            'country' => $country,
            'options' => $options,
            'correct_answer' => $country->capital,
            'difficulty' => $country->difficulty_level,
            'points_base' => 100
        ];
    }

    public function generateContinentQuestion($country): array
    {
        $continents = ['Europa', 'Asia', 'Africa', 'Nord America', 'Sud America', 'Oceania'];
        $wrongContinents = array_filter($continents, fn($c) => $c !== $country->continent);
        $options = array_slice($wrongContinents, 0, 3);
        $options[] = $country->continent;
        shuffle($options);
        
        return [
            'type' => 'continent',
            'question' => "In quale continente si trova {$country->name}?",
            'country' => $country,
            'options' => $options,
            'correct_answer' => $country->continent,
            'difficulty' => $country->difficulty_level,
            'points_base' => 75
        ];
    }

    public function generateTrueFalseQuestion($country): array
    {
        $statements = [
            [
                'text' => "{$country->name} si trova in {$country->continent}",
                'correct' => true
            ],
            [
                'text' => "La capitale di {$country->name} è {$country->capital}",
                'correct' => true
            ],
            [
                'text' => "{$country->name} ha una popolazione superiore ai 100 milioni di abitanti",
                'correct' => $country->population > 100000000
            ]
        ];
        
        // A volte genera una affermazione falsa
        if (rand(0, 1)) {
            $wrongCountry = $this->countryRepository->getRandomCountries(1, [$country->id])->first();
            $statements[] = [
                'text' => "La capitale di {$country->name} è {$wrongCountry->capital}",
                'correct' => false
            ];
        }
        
        $statement = $statements[array_rand($statements)];
        
        return [
            'type' => 'true_false',
            'question' => $statement['text'],
            'country' => $country,
            'options' => ['Vero', 'Falso'],
            'correct_answer' => $statement['correct'] ? 'Vero' : 'Falso',
            'difficulty' => $country->difficulty_level,
            'points_base' => 50
        ];
    }

    public function generateCompleteNameQuestion($country): array
    {
        $name = $country->name;
        $hiddenPart = $this->getHiddenPart($name);
        $questionText = str_replace($hiddenPart, '____', $name);
        
        $similarCountries = $this->countryRepository->getSimilarCountries($country->id, 3);
        $options = $similarCountries->pluck('name')->map(function($similarName) use ($hiddenPart) {
            return $this->extractSimilarPart($similarName, $hiddenPart);
        })->filter()->toArray();
        
        $options[] = $hiddenPart;
        $options = array_unique(array_slice($options, 0, 4));
        
        if (count($options) < 4) {
            $options = array_merge($options, $this->generateRandomWordOptions(4 - count($options)));
        }
        
        shuffle($options);
        
        return [
            'type' => 'complete_name',
            'question' => "Completa il nome del paese: {$questionText}",
            'country' => $country,
            'options' => $options,
            'correct_answer' => $hiddenPart,
            'difficulty' => $country->difficulty_level,
            'points_base' => 125
        ];
    }

    public function calculateBonusResults(): array
    {
        $game = session('bonus_game');
        
        if (!$game) {
            return [];
        }
        
        $totalTime = now()->diffInSeconds($game['start_time']);
        $accuracy = $game['total_questions'] > 0 ? 
            round(($game['correct_answers'] / $game['total_questions']) * 100, 1) : 0;
        
        $rank = $this->calculateRank($accuracy, $game['score'], $game['max_streak']);
        
        return [
            'score' => $game['score'],
            'correct_answers' => $game['correct_answers'],
            'wrong_answers' => $game['wrong_answers'],
            'total_questions' => $game['total_questions'],
            'accuracy' => $accuracy,
            'max_streak' => $game['max_streak'],
            'total_time' => $totalTime,
            'average_time' => round($totalTime / $game['total_questions'], 1),
            'difficulty' => $game['difficulty'],
            'rank' => $rank,
            'answers_history' => $game['answers_history']
        ];
    }

    public function isBonusGameComplete(): bool
    {
        $game = session('bonus_game');
        return $game && $game['current_question'] >= $game['total_questions'];
    }

    public function getBonusGameStats(): array
    {
        $game = session('bonus_game');
        
        if (!$game) {
            return [];
        }
        
        return [
            'current_question' => $game['current_question'] + 1,
            'total_questions' => $game['total_questions'],
            'score' => $game['score'],
            'correct_answers' => $game['correct_answers'],
            'wrong_answers' => $game['wrong_answers'],
            'streak' => $game['streak'],
            'max_streak' => $game['max_streak'],
            'progress' => round(($game['current_question'] / $game['total_questions']) * 100)
        ];
    }

    public function resetBonusGame(): void
    {
        session()->forget('bonus_game');
    }

    // Metodi privati helper

    private function generateRandomQuestion($country): array
    {
        $questionTypes = ['capital', 'continent', 'true_false', 'complete_name'];
        $type = $questionTypes[array_rand($questionTypes)];
        
        return match($type) {
            'capital' => $this->generateCapitalQuestion($country),
            'continent' => $this->generateContinentQuestion($country),
            'true_false' => $this->generateTrueFalseQuestion($country),
            'complete_name' => $this->generateCompleteNameQuestion($country),
        };
    }

    private function getBonusMultiplier(string $difficulty): float
    {
        return match($difficulty) {
            'easy' => 1.0,
            'medium' => 1.5,
            'hard' => 2.0,
            default => 1.0
        };
    }

    private function checkAnswer(string $answer, array $question): bool
    {
        return trim(strtolower($answer)) === trim(strtolower($question['correct_answer']));
    }

    private function getBasePoints(string $questionType): int
    {
        return match($questionType) {
            'capital' => 100,
            'continent' => 75,
            'true_false' => 50,
            'complete_name' => 125,
            default => 50
        };
    }

    private function calculateTimeBonus(array $question): float
    {
        // Bonus per risposte veloci (entro 10 secondi)
        $maxBonusTime = 10;
        $currentTime = now();
        $questionStartTime = $question['start_time'] ?? $currentTime;
        $responseTime = $currentTime->diffInSeconds($questionStartTime);
        
        if ($responseTime <= $maxBonusTime) {
            return ($maxBonusTime - $responseTime) / $maxBonusTime * 0.5; // Max 50% time bonus
        }
        
        return 0;
    }

    private function getExplanation(array $question): string
    {
        $country = $question['country'];
        
        return match($question['type']) {
            'capital' => "La capitale di {$country->name} è {$country->capital}.",
            'continent' => "{$country->name} si trova in {$country->continent}.",
            'true_false' => "L'affermazione è " . strtolower($question['correct_answer']) . ".",
            'complete_name' => "Il nome completo è {$country->name}.",
            default => "Risposta corretta: {$question['correct_answer']}"
        };
    }

    private function getHiddenPart(string $name): string
    {
        $words = explode(' ', $name);
        if (count($words) > 1) {
            return $words[array_rand($words)];
        }
        
        $length = strlen($name);
        if ($length > 6) {
            return substr($name, rand(2, $length - 4), rand(3, min(6, $length - 2)));
        }
        
        return substr($name, 1, -1);
    }

    private function extractSimilarPart(string $name, string $targetPart): ?string
    {
        $words = explode(' ', $name);
        foreach ($words as $word) {
            if (strlen($word) >= 3 && $word !== $targetPart) {
                return $word;
            }
        }
        return null;
    }

    private function generateRandomWordOptions(int $count): array
    {
        $words = ['Stan', 'Land', 'Burg', 'City', 'Town', 'Villa', 'Monte', 'Campo'];
        return array_slice($words, 0, $count);
    }

    private function calculateRank(float $accuracy, int $score, int $maxStreak): array
    {
        if ($accuracy >= 90 && $score >= 1000 && $maxStreak >= 5) {
            return ['name' => 'Genio della Geografia', 'icon' => '🏆', 'color' => 'gold'];
        } elseif ($accuracy >= 80 && $score >= 750) {
            return ['name' => 'Esperto Mondiale', 'icon' => '🥇', 'color' => 'silver'];
        } elseif ($accuracy >= 70 && $score >= 500) {
            return ['name' => 'Viaggiatore Esperto', 'icon' => '🥈', 'color' => 'bronze'];
        } elseif ($accuracy >= 60) {
            return ['name' => 'Esploratore', 'icon' => '🗺️', 'color' => 'blue'];
        } else {
            return ['name' => 'Novizio', 'icon' => '🧭', 'color' => 'green'];
        }
    }

    /**
     * Converte la stringa di difficoltà in numero intero
     */
    private function convertDifficultyToLevel(string $difficulty): int
    {
        return match($difficulty) {
            'easy' => 1,
            'medium' => 2,
            'hard' => 3,
            default => 2 // default medium
        };
    }
}
