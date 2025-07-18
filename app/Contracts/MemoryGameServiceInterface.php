<?php

namespace App\Contracts;

interface MemoryGameServiceInterface
{
    /**
     * Inizia una nuova partita di memory
     */
    public function startGame(int $difficulty = 1, int $pairs = 8): array;
    
    /**
     * Gira una carta
     */
    public function flipCard(array $game, int $cardIndex): array;
    
    /**
     * Controlla se due carte sono accoppiate
     */
    public function checkMatch(array $game, int $card1Index, int $card2Index): array;
    
    /**
     * Calcola i risultati finali
     */
    public function calculateResults(array $game): array;
    
    /**
     * Verifica se il gioco è terminato
     */
    public function isGameComplete(array $game): bool;
}
