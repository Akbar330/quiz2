<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $quizzes = Quiz::with('category')
                      ->withCount('questions')
                      ->paginate(10);
        return view('admin.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.quizzes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'time_limit' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        Quiz::create($request->all());

        return redirect()->route('admin.quizzes.index')
                        ->with('success', 'Quiz berhasil dibuat.');
    }

    public function show(Quiz $quiz)
    {
        $quiz->load(['category', 'questions.options', 'attempts.user']);
        return view('admin.quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        $categories = Category::all();
        return view('admin.quizzes.edit', compact('quiz', 'categories'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'time_limit' => 'required|integer|min:1',
            'is_active' => 'boolean'
        ]);

        $quiz->update($request->all());

        return redirect()->route('admin.quizzes.index')
                        ->with('success', 'Quiz berhasil diupdate.');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('admin.quizzes.index')
                        ->with('success', 'Quiz berhasil dihapus.');
    }
}