<?php

namespace App\Contracts;

use App\Models\QuizScore;
use Illuminate\Database\Eloquent\Collection;

interface QuizScoreRepositoryInterface
{
    /**
     * Salva un nuovo punteggio
     */
    public function save(array $data): QuizScore;

    /**
     * Ottieni i migliori punteggi
     */
    public function getTopScores(int $difficulty, int $limit = 10, ?string $period = null): Collection;

    /**
     * Ottieni statistiche punteggi
     */
    public function getStats(int $difficulty): array;

    /**
     * Trova punteggio per ID
     */
    public function findById(int $id): ?QuizScore;

    /**
     * Ottieni rank di un punteggio
     */
    public function getRank(QuizScore $score): int;

    /**
     * Conta punteggi per difficoltà
     */
    public function countByDifficulty(int $difficulty): int;
    
    /**
     * Salva un punteggio (alias per save)
     */
    public function saveScore(array $data): QuizScore;
    
    /**
     * Ottieni top scores per difficoltà (alias per getTopScores)
     */
    public function getTopScoresByDifficulty(int $difficulty, int $limit = 10): Collection;
}
