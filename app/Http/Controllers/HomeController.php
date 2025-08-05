<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Ambil kategori berdasarkan tipe user
        $categories = Category::where('type', $user->user_type)
                             ->with(['quizzes' => function($query) {
                                 $query->where('is_active', true);
                             }])
                             ->get();

        return view('home', compact('categories'));
    }
}