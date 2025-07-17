<?php

namespace App\Http\Controllers;

use App\Contracts\QuizScoreRepositoryInterface;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    protected $quizScoreRepository;

    public function __construct(QuizScoreRepositoryInterface $quizScoreRepository)
    {
        $this->quizScoreRepository = $quizScoreRepository;
    }

    public function index(Request $request)
    {
        $difficulty = $request->get('difficulty', 1);
        $period = $request->get('period', 'all'); // all, week, month
        
        $topScores = $this->quizScoreRepository->getTopScores($difficulty, 20, $period);
        
        // Statistiche generali usando il repository
        $stats = $this->quizScoreRepository->getStats($difficulty);
        
        return view('leaderboard.index', [
            'topScores' => $topScores,
            'difficulty' => $difficulty,
            'period' => $period,
            'stats' => $stats
        ]);
    }
    
    public function show($id)
    {
        $score = $this->quizScoreRepository->findById($id);
        
        if (!$score) {
            abort(404);
        }
        
        return view('leaderboard.show', [
            'score' => $score,
            'rank' => $this->quizScoreRepository->getRank($score)
        ]);
    }
}
