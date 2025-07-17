<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class QuizController extends Controller
{
    public function index()
    {
        return view('quiz.index');
    }

    public function countriesIndex()
    {
        return view('countries.index');
    }

    public function getQuestion(Request $request)
    {
        $difficulty = $request->get('difficulty', 1);
        $usedCountriesString = $request->get('used_countries', '');
        $usedCountries = !empty($usedCountriesString) ? explode(',', $usedCountriesString) : [];
        
        // Ottieni un paese casuale per la domanda, escludendo quelli già utilizzati
        $query = Country::active();
        
        if ($difficulty) {
            $query->byDifficulty($difficulty);
        }
        
        // Escludi i paesi già utilizzati se ce ne sono
        if (!empty($usedCountries)) {
            $query->whereNotIn('id', $usedCountries);
        }
        
        $correctCountry = $query->get()->shuffle()->first();
        
        if (!$correctCountry) {
            // Se non ci sono più paesi disponibili per questo livello, prova senza filtro difficoltà
            $correctCountry = Country::active()
                ->whereNotIn('id', $usedCountries)
                ->get()
                ->shuffle()
                ->first();
        }
        
        if (!$correctCountry) {
            return response()->json(['error' => 'Nessun paese disponibile'], 404);
        }

        // Ottieni tutti i paesi possibili per le risposte sbagliate
        $allCountries = Country::active()
            ->where('id', '!=', $correctCountry->id)
            ->get();
        
        // Mescola tutti i paesi e prendi i primi 3
        $wrongCountries = $allCountries->shuffle()->take(3);

        // Combina le risposte e mescolale di nuovo
        $answers = collect([$correctCountry])->merge($wrongCountries)->shuffle();

        return response()->json([
            'question' => [
                'flag_url' => $correctCountry->flag_url,
                'continent' => $correctCountry->continent,
                'difficulty' => $correctCountry->difficulty_level
            ],
            'answers' => $answers->map(function ($country) {
                return [
                    'id' => $country->id,
                    'name' => $country->name,
                    'code' => $country->code
                ];
            }),
            'correct_answer_id' => $correctCountry->id
        ]);
    }

    public function checkAnswer(Request $request)
    {
        $request->validate([
            'answer_id' => 'required|exists:countries,id',
            'correct_answer_id' => 'required|exists:countries,id'
        ]);

        $isCorrect = $request->answer_id == $request->correct_answer_id;
        $correctCountry = Country::find($request->correct_answer_id);

        return response()->json([
            'is_correct' => $isCorrect,
            'correct_answer' => [
                'id' => $correctCountry->id,
                'name' => $correctCountry->name,
                'code' => $correctCountry->code,
                'capital' => $correctCountry->capital,
                'continent' => $correctCountry->continent,
                'flag_url' => $correctCountry->flag_url
            ]
        ]);
    }

    public function getStats()
    {
        $stats = [
            'total_countries' => Country::active()->count(),
            'countries_by_difficulty' => Country::active()
                ->selectRaw('difficulty_level, COUNT(*) as count')
                ->groupBy('difficulty_level')
                ->pluck('count', 'difficulty_level'),
            'countries_by_continent' => Country::active()
                ->selectRaw('continent, COUNT(*) as count')
                ->groupBy('continent')
                ->pluck('count', 'continent')
        ];

        return response()->json($stats);
    }

    public function getAllCountries()
    {
        $countries = Country::active()->orderBy('name')->get();
        return response()->json($countries);
    }
}
