<?php

use App\Http\Controllers\CountryController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizSessionController;
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
Route::get('/quiz/results', [QuizSessionController::class, 'results'])->name('quiz.results');

// API Routes (manteniamo per compatibilità)
Route::prefix('api')->group(function () {
    Route::get('/quiz/question', [QuizController::class, 'getQuestion']);
    Route::post('/quiz/check', [QuizController::class, 'checkAnswer']);
    Route::get('/quiz/stats', [QuizController::class, 'getStats']);
});
