<?php

namespace App\Repositories;

use App\Contracts\QuizScoreRepositoryInterface;
use App\Models\QuizScore;
use Illuminate\Database\Eloquent\Collection;

class QuizScoreRepository implements QuizScoreRepositoryInterface
{
    protected $model;

    public function __construct(QuizScore $model)
    {
        $this->model = $model;
    }

    public function save(array $data): QuizScore
    {
        return $this->model->create($data);
    }

    public function getTopScores(int $difficulty, int $limit = 10, ?string $period = null): Collection
    {
        $query = $this->model->byDifficulty($difficulty);

        // Applica filtro temporale se specificato
        switch($period) {
            case 'week':
                $query->recent(7);
                break;
            case 'month':
                $query->recent(30);
                break;
        }

        return $query->topScores($limit)->get();
    }

    public function getStats(int $difficulty): array
    {
        $query = $this->model->byDifficulty($difficulty);

        return [
            'total_games' => $query->count(),
            'avg_percentage' => $query->avg('percentage'),
            'best_time' => $query->min('total_time_seconds'),
            'total_players' => $query->distinct('player_name')->count('player_name'),
            'perfect_scores' => $query->where('percentage', 100)->count(),
            'avg_time' => $query->avg('total_time_seconds')
        ];
    }

    public function findById(int $id): ?QuizScore
    {
        return $this->model->find($id);
    }

    public function getRank(QuizScore $score): int
    {
        return $this->model->where('difficulty_level', $score->difficulty_level)
                          ->where(function($query) use ($score) {
                              $query->where('percentage', '>', $score->percentage)
                                    ->orWhere(function($subQuery) use ($score) {
                                        $subQuery->where('percentage', $score->percentage)
                                                ->where('total_time_seconds', '<', $score->total_time_seconds);
                                    });
                          })
                          ->count() + 1;
    }

    public function countByDifficulty(int $difficulty): int
    {
        return $this->model->byDifficulty($difficulty)->count();
    }
    
    public function saveScore(array $data): QuizScore
    {
        return $this->save($data);
    }
    
    public function getTopScoresByDifficulty(int $difficulty, int $limit = 10): Collection
    {
        return $this->getTopScores($difficulty, $limit);
    }
}
