<?php

namespace App\Contracts;

interface BonusGameServiceInterface
{
    /**
     * Inizia una nuova sessione di gioco bonus
     *
     * @param string $difficulty Difficoltà del gioco (easy, medium, hard)
     * @param int $questionCount Numero di domande bonus
     * @return array
     */
    public function startBonusGame(string $difficulty = 'medium', int $questionCount = 10): array;

    /**
     * Ottiene la domanda bonus corrente
     *
     * @return array|null
     */
    public function getCurrentBonusQuestion(): ?array;

    /**
     * Processa la risposta alla domanda bonus
     *
     * @param string $answer Risposta data dall'utente
     * @return array
     */
    public function processBonusAnswer(string $answer): array;

    /**
     * Genera una domanda di tipo "Indovina la capitale"
     *
     * @param object $country Paese per cui generare la domanda
     * @return array
     */
    public function generateCapitalQuestion($country): array;

    /**
     * Genera una domanda di tipo "Quale continente?"
     *
     * @param object $country Paese per cui generare la domanda
     * @return array
     */
    public function generateContinentQuestion($country): array;

    /**
     * Genera una domanda di tipo "Vero o Falso"
     *
     * @param object $country Paese per cui generare la domanda
     * @return array
     */
    public function generateTrueFalseQuestion($country): array;

    /**
     * Genera una domanda di tipo "Completa il nome"
     *
     * @param object $country Paese per cui generare la domanda
     * @return array
     */
    public function generateCompleteNameQuestion($country): array;

    /**
     * Calcola i risultati finali del gioco bonus
     *
     * @return array
     */
    public function calculateBonusResults(): array;

    /**
     * Verifica se il gioco bonus è completato
     *
     * @return bool
     */
    public function isBonusGameComplete(): bool;

    /**
     * Ottiene le statistiche attuali del gioco bonus
     *
     * @return array
     */
    public function getBonusGameStats(): array;

    /**
     * Resetta la sessione di gioco bonus
     *
     * @return void
     */
    public function resetBonusGame(): void;
}
