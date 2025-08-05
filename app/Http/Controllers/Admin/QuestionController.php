<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Quiz $quiz)
    {
        $questions = $quiz->questions()->with('options')->paginate(10);
        return view('admin.questions.index', compact('quiz', 'questions'));
    }

    public function create(Quiz $quiz)
    {
        return view('admin.questions.create', compact('quiz'));
    }

    public function store(Request $request, Quiz $quiz)
    {
        $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false',
            'points' => 'required|integer|min:1',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer'
        ]);

        $question = $quiz->questions()->create([
            'question' => $request->question,
            'type' => $request->type,
            'points' => $request->points
        ]);

        foreach ($request->options as $index => $optionText) {
            $question->options()->create([
                'option_text' => $optionText,
                'is_correct' => $index == $request->correct_option
            ]);
        }

        return redirect()->route('admin.questions.index', $quiz)
                        ->with('success', 'Soal berhasil dibuat.');
    }

    public function edit(Quiz $quiz, Question $question)
    {
        $question->load('options');
        return view('admin.questions.edit', compact('quiz', 'question'));
    }

    public function update(Request $request, Quiz $quiz, Question $question)
    {
        $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false',
            'points' => 'required|integer|min:1',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer'
        ]);

        $question->update([
            'question' => $request->question,
            'type' => $request->type,
            'points' => $request->points
        ]);

        // Delete existing options
        $question->options()->delete();

        // Create new options
        foreach ($request->options as $index => $optionText) {
            $question->options()->create([
                'option_text' => $optionText,
                'is_correct' => $index == $request->correct_option
            ]);
        }

        return redirect()->route('admin.questions.index', $quiz)
                        ->with('success', 'Soal berhasil diupdate.');
    }

    public function destroy(Quiz $quiz, Question $question)
    {
        $question->delete();

        return redirect()->route('admin.questions.index', $quiz)
                        ->with('success', 'Soal berhasil dihapus.');
    }
}