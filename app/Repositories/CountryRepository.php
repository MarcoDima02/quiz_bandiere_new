<?php

namespace App\Repositories;

use App\Contracts\CountryRepositoryInterface;
use App\Models\Country;
use Illuminate\Database\Eloquent\Collection;

class CountryRepository implements CountryRepositoryInterface
{
    protected $model;

    public function __construct(Country $model)
    {
        $this->model = $model;
    }

    public function getAllActive(): Collection
    {
        return $this->model->active()->orderBy('name')->get();
    }

    public function getByDifficulty(int $difficulty): Collection
    {
        return $this->model->active()
                          ->where('difficulty_level', '<=', $difficulty)
                          ->get();
    }

    public function getByContinent(string $continent): Collection
    {
        return $this->model->active()
                          ->where('continent', $continent)
                          ->orderBy('name')
                          ->get();
    }

    public function getRandomForQuiz(int $difficulty, array $excludeIds = [], int $limit = 1): Collection
    {
        $query = $this->model->active()
                            ->where('difficulty_level', '<=', $difficulty);

        if (!empty($excludeIds)) {
            $query->whereNotIn('id', $excludeIds);
        }

        return $query->inRandomOrder()->limit($limit)->get();
    }

    public function getWrongAnswers(int $correctAnswerId, int $difficulty, int $limit = 3): Collection
    {
        // Prima prova con lo stesso continente per maggiore difficoltà
        $correctCountry = $this->findById($correctAnswerId);
        
        $wrongAnswers = $this->model->active()
                                   ->where('id', '!=', $correctAnswerId)
                                   ->where('continent', $correctCountry->continent)
                                   ->inRandomOrder()
                                   ->limit($limit)
                                   ->get();

        // Se non ci sono abbastanza risposte dello stesso continente
        if ($wrongAnswers->count() < $limit) {
            $additional = $this->model->active()
                                     ->where('id', '!=', $correctAnswerId)
                                     ->whereNotIn('id', $wrongAnswers->pluck('id'))
                                     ->inRandomOrder()
                                     ->limit($limit - $wrongAnswers->count())
                                     ->get();
                                     
            $wrongAnswers = $wrongAnswers->merge($additional);
        }

        return $wrongAnswers;
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function getStats(): array
    {
        return [
            'total_countries' => $this->model->active()->count(),
            'countries_by_difficulty' => $this->model->active()
                ->selectRaw('difficulty_level, COUNT(*) as count')
                ->groupBy('difficulty_level')
                ->pluck('count', 'difficulty_level')
                ->toArray(),
            'countries_by_continent' => $this->model->active()
                ->selectRaw('continent, COUNT(*) as count')
                ->groupBy('continent')
                ->pluck('count', 'continent')
                ->toArray()
        ];
    }

    public function getRandomCapitals(int $limit = 3, array $excludeIds = []): Collection
    {
        $query = $this->model->active()->whereNotNull('capital');

        if (!empty($excludeIds)) {
            $query->whereNotIn('id', $excludeIds);
        }

        return $query->inRandomOrder()->limit($limit)->get();
    }

    public function getRandomCountries(int $limit = 1, array $excludeIds = []): Collection
    {
        $query = $this->model->active();

        if (!empty($excludeIds)) {
            $query->whereNotIn('id', $excludeIds);
        }

        return $query->inRandomOrder()->limit($limit)->get();
    }

    public function getSimilarCountries(int $countryId, int $limit = 3): Collection
    {
        $country = $this->findById($countryId);
        
        if (!$country) {
            return collect();
        }

        // Prima cerca paesi dello stesso continente
        $similar = $this->model->active()
                              ->where('id', '!=', $countryId)
                              ->where('continent', $country->continent)
                              ->inRandomOrder()
                              ->limit($limit)
                              ->get();

        // Se non ci sono abbastanza paesi simili, aggiungi altri casuali
        if ($similar->count() < $limit) {
            $additional = $this->model->active()
                                     ->where('id', '!=', $countryId)
                                     ->whereNotIn('id', $similar->pluck('id'))
                                     ->inRandomOrder()
                                     ->limit($limit - $similar->count())
                                     ->get();
                                     
            $similar = $similar->merge($additional);
        }

        return $similar;
    }
}
