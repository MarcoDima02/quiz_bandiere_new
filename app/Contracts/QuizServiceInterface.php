<?php

namespace App\Contracts;

interface QuizServiceInterface
{
    /**
     * Inizia un nuovo quiz
     */
    public function startQuiz(int $difficulty, int $totalQuestions): array;

    /**
     * Genera una nuova domanda
     */
    public function generateQuestion(array $quiz): array;

    /**
     * Processa una risposta
     */
    public function processAnswer(array $quiz, int $selectedAnswerId, int $correctAnswerId): array;

    /**
     * Calcola i risultati finali
     */
    public function calculateResults(array $quiz): array;

    /**
     * Salva il punteggio finale
     */
    public function saveScore(array $quiz, string $playerName, string $playerIp): \App\Models\QuizScore;

    /**
     * Ottieni le statistiche del quiz
     */
    public function getQuizStats(): array;
}
