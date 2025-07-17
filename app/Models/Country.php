<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'flag_url',
        'capital',
        'continent',
        'difficulty_level',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'difficulty_level' => 'integer'
    ];

    // Scope per ottenere solo i paesi attivi
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope per ottenere paesi per livello di difficoltà
    public function scopeByDifficulty($query, $level)
    {
        return $query->where('difficulty_level', $level);
    }

    // Scope per ottenere paesi per continente
    public function scopeByContinent($query, $continent)
    {
        return $query->where('continent', $continent);
    }

    // Metodo per ottenere paesi casuali per il quiz
    public static function getRandomCountries($count = 4, $difficulty = null)
    {
        $query = self::active();
        
        if ($difficulty) {
            $query->byDifficulty($difficulty);
        }
        
        return $query->inRandomOrder()->limit($count)->get();
    }

    // Metodo per ottenere un paese casuale per la domanda
    public static function getRandomCountryForQuestion($difficulty = null)
    {
        $query = self::active();
        
        if ($difficulty) {
            $query->byDifficulty($difficulty);
        }
        
        // Usa una seed basata su timestamp per migliorare la randomizzazione
        $countries = $query->get();
        
        if ($countries->isEmpty()) {
            return null;
        }
        
        // Randomizzazione migliorata con shuffle
        $shuffled = $countries->shuffle();
        return $shuffled->first();
    }
}
