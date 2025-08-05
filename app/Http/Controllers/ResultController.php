<?php

namespace App\Http\Controllers;

use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(QuizAttempt $attempt)
    {
        if ($attempt->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $attempt->load([
            'quiz.questions.options',
            'userAnswers.question.options',
            'userAnswers.option'
        ]);

        return view('results.show', compact('attempt'));
    }

    public function index()
    {
        $attempts = QuizAttempt::where('user_id', auth()->id())
                              ->with('quiz')
                              ->latest()
                              ->paginate(10);

        return view('results.index', compact('attempts'));
    }
}