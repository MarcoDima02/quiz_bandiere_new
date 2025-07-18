<?php

namespace App\Http\Controllers;

use App\Contracts\BonusGameServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BonusGameController extends Controller
{
    protected BonusGameServiceInterface $bonusGameService;

    public function __construct(BonusGameServiceInterface $bonusGameService)
    {
        $this->bonusGameService = $bonusGameService;
    }

    /**
     * Mostra la pagina principale del gioco bonus
     */
    public function index(): View
    {
        $this->bonusGameService->resetBonusGame();
        
        return view('bonus.index', [
            'title' => 'Gioco Bonus - Domande Speciali'
        ]);
    }

    /**
     * Inizia una nuova sessione di gioco bonus
     */
    public function start(Request $request): JsonResponse
    {
        $request->validate([
            'difficulty' => 'required|in:easy,medium,hard',
            'question_count' => 'integer|min:5|max:20'
        ]);

        $difficulty = $request->input('difficulty', 'medium');
        $questionCount = $request->input('question_count', 10);

        $game = $this->bonusGameService->startBonusGame($difficulty, $questionCount);

        return response()->json([
            'success' => true,
            'game' => $game,
            'redirect_url' => route('bonus.game')
        ]);
    }

    /**
     * Mostra la vista del gioco in corso
     */
    public function game(): View|RedirectResponse
    {
        $currentQuestion = $this->bonusGameService->getCurrentBonusQuestion();
        $stats = $this->bonusGameService->getBonusGameStats();

        if (!$currentQuestion) {
            return redirect()->route('bonus.index')
                           ->with('error', 'Nessun gioco attivo. Inizia una nuova partita!');
        }

        return view('bonus.game', [
            'question' => $currentQuestion,
            'stats' => $stats,
            'is_complete' => $this->bonusGameService->isBonusGameComplete()
        ]);
    }

    /**
     * Processa la risposta via AJAX
     */
    public function answer(Request $request): JsonResponse
    {
        $request->validate([
            'answer' => 'required|string'
        ]);

        $result = $this->bonusGameService->processBonusAnswer($request->input('answer'));

        if (isset($result['error'])) {
            return response()->json([
                'success' => false,
                'error' => $result['error']
            ], 400);
        }

        $response = [
            'success' => true,
            'result' => $result,
            'stats' => $this->bonusGameService->getBonusGameStats()
        ];

        if ($result['is_complete']) {
            $response['results'] = $this->bonusGameService->calculateBonusResults();
            $response['redirect_url'] = route('bonus.results');
        } else {
            $response['next_question'] = $this->bonusGameService->getCurrentBonusQuestion();
        }

        return response()->json($response);
    }

    /**
     * Mostra i risultati finali
     */
    public function results(): View|RedirectResponse
    {
        if (!$this->bonusGameService->isBonusGameComplete()) {
            return redirect()->route('bonus.index')
                           ->with('error', 'Completa prima il gioco bonus!');
        }

        $results = $this->bonusGameService->calculateBonusResults();

        return view('bonus.results', [
            'results' => $results
        ]);
    }

    /**
     * Ottieni la domanda corrente via AJAX
     */
    public function currentQuestion(): JsonResponse
    {
        $question = $this->bonusGameService->getCurrentBonusQuestion();
        $stats = $this->bonusGameService->getBonusGameStats();

        if (!$question) {
            return response()->json([
                'success' => false,
                'error' => 'Nessuna domanda attiva'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'question' => $question,
            'stats' => $stats
        ]);
    }

    /**
     * Abbandona il gioco corrente
     */
    public function quit(): JsonResponse
    {
        $this->bonusGameService->resetBonusGame();

        return response()->json([
            'success' => true,
            'message' => 'Gioco abbandonato',
            'redirect_url' => route('bonus.index')
        ]);
    }

    /**
     * Ottieni le statistiche attuali
     */
    public function stats(): JsonResponse
    {
        $stats = $this->bonusGameService->getBonusGameStats();

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
