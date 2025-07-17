<?php

namespace App\Http\Controllers;

use App\Contracts\QuizServiceInterface;
use App\Contracts\QuizScoreRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class QuizSessionController extends Controller
{
    protected $quizService;
    protected $quizScoreRepository;

    public function __construct(
        QuizServiceInterface $quizService,
        QuizScoreRepositoryInterface $quizScoreRepository
    ) {
        $this->quizService = $quizService;
        $this->quizScoreRepository = $quizScoreRepository;
    }

    public function index()
    {
        return view('quiz.index');
    }

    public function start(Request $request)
    {
        // Reset sessione quiz e flag di salvataggio punteggio
        Session::forget('quiz');
        Session::forget('quiz_score_saved');
        
        // Configura il quiz tramite service
        $quiz = $this->quizService->startQuiz(
            $request->input('difficulty', 1),
            $request->input('questions', 10)
        );
        
        Session::put('quiz', $quiz);
        
        return redirect()->route('quiz.question');
    }
    
    public function question()
    {
        $quiz = Session::get('quiz');
        
        if (!$quiz) {
            return redirect()->route('quiz.index')->with('error', 'Quiz non trovato. Inizia un nuovo quiz.');
        }
        
        // Se abbiamo completato tutte le domande
        if ($quiz['current_question'] >= $quiz['total_questions']) {
            return redirect()->route('quiz.results');
        }
        
        // Se non abbiamo ancora generato questa domanda
        if (!isset($quiz['questions'][$quiz['current_question']])) {
            $questionData = $this->quizService->generateQuestion($quiz);
            
            $quiz = $questionData['quiz']; // Quiz aggiornato dal service
            Session::put('quiz', $quiz);
        }
        
        $question = $quiz['questions'][$quiz['current_question']];
        
        return view('quiz.question', [
            'quiz' => $quiz,
            'country' => $question['country'],
            'answers' => $question['answers'],
            'questionNumber' => $quiz['current_question'] + 1,
            'totalQuestions' => $quiz['total_questions']
        ]);
    }
    
    public function answer(Request $request)
    {
        $quiz = Session::get('quiz');
        
        if (!$quiz) {
            return redirect()->route('quiz.index')->with('error', 'Quiz non trovato. Inizia un nuovo quiz.');
        }
        
        $selectedAnswerId = $request->input('answer_id');
        $currentQuestion = $quiz['current_question'];
        $correctAnswerId = $quiz['questions'][$currentQuestion]['correct_answer_id'];
        
        $answerResult = $this->quizService->processAnswer($quiz, $selectedAnswerId, $correctAnswerId);
        
        // Aggiorna la sessione con il quiz modificato
        $quiz = $answerResult['quiz'];
        
        // Passa alla domanda successiva
        $quiz['current_question']++;
        Session::put('quiz', $quiz);
        
        return view('quiz.answer', [
            'quiz' => $quiz,
            'isCorrect' => $answerResult['is_correct'],
            'correctCountry' => $answerResult['correct_country'],
            'selectedAnswer' => $answerResult['selected_answer'],
            'questionNumber' => $currentQuestion + 1,
            'totalQuestions' => $quiz['total_questions']
        ]);
    }
    
    public function results(Request $request)
    {
        $quiz = Session::get('quiz');
        
        if (!$quiz) {
            return redirect()->route('quiz.index')->with('error', 'Quiz non trovato. Inizia un nuovo quiz.');
        }
        
        $results = $this->quizService->calculateResults($quiz);
        
        // Salva il punteggio se non è già stato salvato
        $quizScore = null;
        if (!Session::has('quiz_score_saved') && $request->has('player_name') && !empty($request->player_name)) {
            $quizScore = $this->quizScoreRepository->saveScore([
                'player_name' => $request->player_name,
                'score' => $results['score'],
                'total_questions' => $results['total'],
                'percentage' => $results['percentage'],
                'difficulty_level' => $quiz['difficulty'],
                'total_time_seconds' => $results['total_time'],
                'avg_time_per_question' => $results['avg_time_per_question'],
                'quiz_details' => [
                    'questions' => $quiz['questions'],
                    'answers' => $quiz['answers'],
                    'used_countries' => $quiz['used_countries']
                ],
                'player_ip' => $request->ip()
            ]);
            
            Session::put('quiz_score_saved', true);
        }
        
        // Ottieni i migliori punteggi per questa difficoltà
        $topScores = $this->quizScoreRepository->getTopScoresByDifficulty($quiz['difficulty'], 5);
        
        $showNameForm = !Session::has('quiz_score_saved') && !$request->has('player_name');
        
        return view('quiz.results', array_merge($results, [
            'quiz' => $quiz,
            'quizScore' => $quizScore,
            'topScores' => $topScores,
            'showNameForm' => $showNameForm
        ]));
    }
}
