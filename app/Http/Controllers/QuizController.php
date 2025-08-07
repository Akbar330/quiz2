<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Category;
use App\Models\UserAnswer;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
{
    $categories = Category::all(); // Pastikan model Category ada

    return view('admin.quizzes.create', compact('categories'));
}


    public function show(Quiz $quiz)
    {
        $quiz->load('questions.options');
        return view('quiz.show', compact('quiz'));
    }

    public function start(Quiz $quiz)
    {
        // Check if user already has an unfinished attempt
        $existingAttempt = QuizAttempt::where('user_id', auth()->id())
                                    ->where('quiz_id', $quiz->id)
                                    ->whereNull('finished_at')
                                    ->first();

        if ($existingAttempt) {
            return redirect()->route('quiz.take', [$quiz, $existingAttempt]);
        }

        // Create new attempt
        $attempt = QuizAttempt::create([
            'user_id' => auth()->id(),
            'quiz_id' => $quiz->id,
            'total_questions' => $quiz->questions()->count(),
            'started_at' => now()
        ]);

        return redirect()->route('quiz.take', [$quiz, $attempt]);
    }

    public function take(Quiz $quiz, QuizAttempt $attempt)
    {
        if ($attempt->user_id !== auth()->id()) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $quiz->load('questions.options');
        
        // Check if time limit exceeded
        $timeElapsed = now()->diffInMinutes($attempt->started_at);
        if ($timeElapsed >= $quiz->time_limit) {
            return $this->submitQuiz($quiz, $attempt, new Request());
        }

        return view('quiz.take', compact('quiz', 'attempt'));
    }

    public function submit(Quiz $quiz, QuizAttempt $attempt, Request $request)
    {
        if ($attempt->user_id !== auth()->id()) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        return $this->submitQuiz($quiz, $attempt, $request);
    }

    private function submitQuiz(Quiz $quiz, QuizAttempt $attempt, Request $request)
    {
        DB::transaction(function () use ($quiz, $attempt, $request) {
            $correctAnswers = 0;
            $totalScore = 0;

            foreach ($quiz->questions as $question) {
                $selectedOptionId = $request->input("question_{$question->id}");
                
                if ($selectedOptionId) {
                    $selectedOption = $question->options()->find($selectedOptionId);
                    $isCorrect = $selectedOption && $selectedOption->is_correct;

                    UserAnswer::create([
                        'quiz_attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                        'option_id' => $selectedOptionId,
                        'is_correct' => $isCorrect
                    ]);

                    if ($isCorrect) {
                        $correctAnswers++;
                        $totalScore += $question->points;
                    }
                }
            }

            $attempt->update([
                'correct_answers' => $correctAnswers,
                'score' => $totalScore,
                'finished_at' => now()
            ]);
        });

        return redirect()->route('quiz.result', $attempt);
    }
}