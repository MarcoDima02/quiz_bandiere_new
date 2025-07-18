<?php

namespace App\Http\Controllers;

use App\Contracts\MemoryGameServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MemoryGameController extends Controller
{
    protected $memoryGameService;

    public function __construct(MemoryGameServiceInterface $memoryGameService)
    {
        $this->memoryGameService = $memoryGameService;
    }

    public function index()
    {
        return view('memory.index');
    }

    public function start(Request $request)
    {
        // Reset sessione gioco
        Session::forget('memory_game');
        
        $difficulty = $request->input('difficulty', 1);
        $pairs = $request->input('pairs', 8);
        
        // Configura il gioco tramite service
        $game = $this->memoryGameService->startGame($difficulty, $pairs);
        
        Session::put('memory_game', $game);
        
        return redirect()->route('memory.game');
    }

    public function game()
    {
        $game = Session::get('memory_game');
        
        if (!$game) {
            return redirect()->route('memory.index')->with('error', 'Gioco non trovato. Inizia una nuova partita.');
        }
        
        // Se il gioco è completato, vai ai risultati
        if ($game['is_complete']) {
            return redirect()->route('memory.results');
        }
        
        return view('memory.game', compact('game'));
    }

    public function flipCard(Request $request)
    {
        $game = Session::get('memory_game');
        
        if (!$game) {
            return response()->json(['error' => 'Gioco non trovato'], 404);
        }
        
        $cardIndex = $request->input('card_index');
        
        // Gira la carta tramite service
        $game = $this->memoryGameService->flipCard($game, $cardIndex);
        
        Session::put('memory_game', $game);
        
        return response()->json([
            'success' => true,
            'game' => $game,
            'is_complete' => $game['is_complete']
        ]);
    }

    public function results()
    {
        $game = Session::get('memory_game');
        
        if (!$game) {
            return redirect()->route('memory.index')->with('error', 'Gioco non trovato. Inizia una nuova partita.');
        }
        
        $results = $this->memoryGameService->calculateResults($game);
        
        return view('memory.results', compact('game', 'results'));
    }
}
