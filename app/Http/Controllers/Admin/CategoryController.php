<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $categories = Category::withCount('quizzes')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:student,public',
            'description' => 'nullable|string'
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Kategori berhasil dibuat.');
    }

    public function show(Category $category)
    {
        $category->load('quizzes');
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:student,public',
            'description' => 'nullable|string'
        ]);

        $category->update($request->all());

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Kategori berhasil diupdate.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Kategori berhasil dihapus.');
    }
}