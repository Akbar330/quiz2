<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Category;
use App\Models\UserAnswer;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Question;

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

    public function showQuestionsForMasyarakat()
    {
        $question = \App\Models\Question::with('options')
            ->where('quiz_id', 1)
            ->inRandomOrder()
            ->first();

        // Bungkus jadi array agar bisa dipakai @foreach
        $questions = collect([$question]);

        return view('dashboards.masyarakat', compact('questions'));
    }

    public function checkAnswer(Request $request)
    {
        $option = \App\Models\Option::find($request->option_id);

        if (!$option) {
            return back()->with('error', 'Opsi tidak ditemukan.');
        }

        if ($option->is_correct) {
            return back()->with('success', 'Jawaban Anda BENAR!');
        } else {
            return back()->with('error', 'Jawaban Anda SALAH!');
        }
    }

    public function gachaQuestion()
    {
        $question = \App\Models\Question::with('options')
            ->where('quiz_id', 1)
            ->inRandomOrder()
            ->first();

        return view('dashboards.masyarakat', ['question' => $question]);
    }

    public function showPelajarQuestions()
    {
        $question = \App\Models\Question::with('options')
            ->where('quiz_id', 2) // khusus pelajar
            ->inRandomOrder()
            ->first();

        $questions = collect([$question]); // biar bisa di-foreach

        return view('dashboards.pelajar', compact('questions'));
    }

    public function checkPelajarAnswer(Request $request)
    {
        $option = \App\Models\Option::find($request->option_id);

        if (!$option) {
            return redirect()->back()->with('error', 'Jawaban tidak ditemukan.');
        }

        if ($option->is_correct) {
            return redirect()->back()->with('success', 'Jawaban Anda BENAR!');
        } else {
            return redirect()->back()->with('error', 'Jawaban Anda SALAH!');
        }
    }
}
