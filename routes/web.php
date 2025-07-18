<?php

use App\Http\Controllers\CountryController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizSessionController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\MemoryGameController;
use App\Http\Controllers\BonusGameController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route per i paesi
Route::get('/countries', [CountryController::class, 'index'])->name('countries.index');

// Route per il quiz con sessioni PHP
Route::get('/quiz', [QuizSessionController::class, 'index'])->name('quiz.index');
Route::post('/quiz/start', [QuizSessionController::class, 'start'])->name('quiz.start');
Route::get('/quiz/question', [QuizSessionController::class, 'question'])->name('quiz.question');
Route::post('/quiz/answer', [QuizSessionController::class, 'answer'])->name('quiz.answer');
Route::get('/quiz/next', [QuizSessionController::class, 'nextQuestion'])->name('quiz.next');
Route::get('/quiz/results', [QuizSessionController::class, 'results'])->name('quiz.results');

// Route per le classifiche
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
Route::get('/leaderboard/{id}', [LeaderboardController::class, 'show'])->name('leaderboard.show');

// Route per il Memory Game
Route::get('/memory', [MemoryGameController::class, 'index'])->name('memory.index');
Route::post('/memory/start', [MemoryGameController::class, 'start'])->name('memory.start');
Route::get('/memory/game', [MemoryGameController::class, 'game'])->name('memory.game');
Route::post('/memory/flip', [MemoryGameController::class, 'flipCard'])->name('memory.flip');
Route::get('/memory/results', [MemoryGameController::class, 'results'])->name('memory.results');

// Route per il Bonus Game
Route::get('/bonus', [BonusGameController::class, 'index'])->name('bonus.index');
Route::post('/bonus/start', [BonusGameController::class, 'start'])->name('bonus.start');
Route::get('/bonus/game', [BonusGameController::class, 'game'])->name('bonus.game');
Route::post('/bonus/answer', [BonusGameController::class, 'answer'])->name('bonus.answer');
Route::get('/bonus/results', [BonusGameController::class, 'results'])->name('bonus.results');
Route::get('/bonus/current', [BonusGameController::class, 'currentQuestion'])->name('bonus.current');
Route::post('/bonus/quit', [BonusGameController::class, 'quit'])->name('bonus.quit');
Route::get('/bonus/stats', [BonusGameController::class, 'stats'])->name('bonus.stats');

// API Routes (manteniamo per compatibilità)
Route::prefix('api')->group(function () {
    Route::get('/quiz/question', [QuizController::class, 'getQuestion']);
    Route::post('/quiz/check', [QuizController::class, 'checkAnswer']);
    Route::get('/quiz/stats', [QuizController::class, 'getStats']);
});
