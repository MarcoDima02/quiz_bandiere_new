<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface CountryRepositoryInterface
{
    /**
     * Ottieni tutti i paesi attivi
     */
    public function getAllActive(): Collection;

    /**
     * Ottieni paesi per difficoltà
     */
    public function getByDifficulty(int $difficulty): Collection;

    /**
     * Ottieni paesi per continente
     */
    public function getByContinent(string $continent): Collection;

    /**
     * Ottieni paesi casuali per il quiz
     */
    public function getRandomForQuiz(int $difficulty, array $excludeIds = [], int $limit = 1): Collection;

    /**
     * Ottieni risposte sbagliate per una domanda
     */
    public function getWrongAnswers(int $correctAnswerId, int $difficulty, int $limit = 3): Collection;

    /**
     * Trova paese per ID
     */
    public function findById(int $id);

    /**
     * Ottieni statistiche dei paesi
     */
    public function getStats(): array;
}
