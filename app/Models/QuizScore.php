<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuizScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_name',
        'score',
        'total_questions',
        'percentage',
        'difficulty_level',
        'total_time_seconds',
        'avg_time_per_question',
        'quiz_details',
        'player_ip'
    ];

    protected $casts = [
        'quiz_details' => 'array',
        'percentage' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Scope per ottenere i migliori punteggi
    public function scopeTopScores($query, $limit = 10)
    {
        return $query->orderBy('percentage', 'desc')
                    ->orderBy('total_time_seconds', 'asc')
                    ->limit($limit);
    }

    // Scope per punteggi per difficoltà
    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    // Scope per punteggi recenti
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Metodo per ottenere la posizione nella classifica
    public function getRankAttribute()
    {
        return self::where('difficulty_level', $this->difficulty_level)
                  ->where(function($query) {
                      $query->where('percentage', '>', $this->percentage)
                            ->orWhere(function($subQuery) {
                                $subQuery->where('percentage', $this->percentage)
                                        ->where('total_time_seconds', '<', $this->total_time_seconds);
                            });
                  })
                  ->count() + 1;
    }

    // Metodo per formattare il tempo
    public function getFormattedTimeAttribute()
    {
        $minutes = floor($this->total_time_seconds / 60);
        $seconds = $this->total_time_seconds % 60;
        return sprintf('%d:%02d', $minutes, $seconds);
    }

    // Metodo per ottenere il badge di performance
    public function getPerformanceBadgeAttribute()
    {
        if ($this->percentage >= 90) return '🏆';
        if ($this->percentage >= 70) return '🥈';
        if ($this->percentage >= 50) return '🥉';
        return '📚';
    }

    // Metodo per ottenere il livello di difficoltà come stringa
    public function getDifficultyLabelAttribute()
    {
        return match($this->difficulty_level) {
            1 => 'Facile',
            2 => 'Medio', 
            3 => 'Difficile',
            default => 'Sconosciuto'
        };
    }
}
