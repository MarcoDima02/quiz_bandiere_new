<?php

namespace App\Services;

use App\Contracts\MemoryGameServiceInterface;
use App\Contracts\CountryRepositoryInterface;

class MemoryGameService implements MemoryGameServiceInterface
{
    protected $countryRepository;

    public function __construct(CountryRepositoryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function startGame(int $difficulty = 1, int $pairs = 8): array
    {
        // Ottieni paesi casuali per il livello di difficoltà
        $countries = $this->countryRepository->getRandomForQuiz($difficulty, [], $pairs);
        
        // Crea le carte (ogni paese appare due volte)
        $cards = [];
        foreach ($countries as $country) {
            // Prima carta
            $cards[] = [
                'id' => $country->id,
                'country' => $country,
                'flag_url' => 'https://flagsapi.com/' . $country->code . '/flat/64.png',
                'is_flipped' => false,
                'is_matched' => false
            ];
            
            // Seconda carta (coppia)
            $cards[] = [
                'id' => $country->id,
                'country' => $country,
                'flag_url' => 'https://flagsapi.com/' . $country->code . '/flat/64.png',
                'is_flipped' => false,
                'is_matched' => false
            ];
        }
        
        // Mescola le carte
        shuffle($cards);
        
        // Aggiungi indici univoci per il tracking
        foreach ($cards as $index => &$card) {
            $card['index'] = $index;
        }
        
        return [
            'difficulty' => $difficulty,
            'total_pairs' => $pairs,
            'cards' => $cards,
            'flipped_cards' => [],
            'matched_pairs' => 0,
            'moves' => 0,
            'start_time' => now(),
            'is_complete' => false,
            'score' => 0
        ];
    }

    public function flipCard(array $game, int $cardIndex): array
    {
        // Verifica che l'indice sia valido
        if (!isset($game['cards'][$cardIndex])) {
            return $game;
        }
        
        $card = &$game['cards'][$cardIndex];
        
        // Non girare carte già accoppiate o già girate
        if ($card['is_matched'] || $card['is_flipped']) {
            return $game;
        }
        
        // Se abbiamo già due carte girate, reset prima
        if (count($game['flipped_cards']) >= 2) {
            foreach ($game['flipped_cards'] as $flippedIndex) {
                if (!$game['cards'][$flippedIndex]['is_matched']) {
                    $game['cards'][$flippedIndex]['is_flipped'] = false;
                }
            }
            $game['flipped_cards'] = [];
        }
        
        // Gira la carta
        $card['is_flipped'] = true;
        $game['flipped_cards'][] = $cardIndex;
        
        // Se abbiamo due carte girate, controlla se combaciano
        if (count($game['flipped_cards']) == 2) {
            $game = $this->checkMatch(
                $game, 
                $game['flipped_cards'][0], 
                $game['flipped_cards'][1]
            );
        }
        
        return $game;
    }

    public function checkMatch(array $game, int $card1Index, int $card2Index): array
    {
        $card1 = $game['cards'][$card1Index];
        $card2 = $game['cards'][$card2Index];
        
        $game['moves']++;
        
        // Controlla se le carte combaciano (stesso paese)
        if ($card1['id'] == $card2['id']) {
            // Match trovato!
            $game['cards'][$card1Index]['is_matched'] = true;
            $game['cards'][$card2Index]['is_matched'] = true;
            $game['matched_pairs']++;
            
            // Calcola punteggio bonus per velocità
            $timeBonus = max(0, 100 - $game['moves']);
            $game['score'] += 100 + $timeBonus;
            
        } else {
            // Nessun match - gira di nuovo le carte dopo un delay
            $game['cards'][$card1Index]['is_flipped'] = false;
            $game['cards'][$card2Index]['is_flipped'] = false;
        }
        
        // Reset carte girate
        $game['flipped_cards'] = [];
        
        // Controlla se il gioco è completo
        $game['is_complete'] = $this->isGameComplete($game);
        
        return $game;
    }

    public function calculateResults(array $game): array
    {
        $totalTime = now()->diffInSeconds($game['start_time']);
        $efficiency = round(($game['total_pairs'] / max(1, $game['moves'])) * 100);
        
        // Determina messaggio performance
        [$message, $icon] = $this->getPerformanceMessage($efficiency, $totalTime);
        
        return [
            'matched_pairs' => $game['matched_pairs'],
            'total_pairs' => $game['total_pairs'],
            'moves' => $game['moves'],
            'score' => $game['score'],
            'total_time' => $totalTime,
            'efficiency' => $efficiency,
            'message' => $message,
            'icon' => $icon,
            'difficulty' => $game['difficulty']
        ];
    }

    public function isGameComplete(array $game): bool
    {
        return $game['matched_pairs'] >= $game['total_pairs'];
    }

    protected function getPerformanceMessage(int $efficiency, int $totalTime): array
    {
        if ($efficiency >= 80 && $totalTime <= 60) {
            return ['🏆 Incredibile! Memoria da campione!', '🏆'];
        } elseif ($efficiency >= 60 && $totalTime <= 120) {
            return ['🥈 Ottimo lavoro! Hai una buona memoria!', '🥈'];
        } elseif ($efficiency >= 40) {
            return ['🥉 Bene! Continua a praticare!', '🥉'];
        } else {
            return ['📚 Buon tentativo! La pratica rende perfetti!', '📚'];
        }
    }
}
