<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalQuizzes = Quiz::count();
        $totalAttempts = QuizAttempt::count();
        $totalCategories = Category::count();

        $recentAttempts = QuizAttempt::with(['user', 'quiz'])
                                   ->latest()
                                   ->limit(10)
                                   ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalQuizzes',
            'totalAttempts',
            'totalCategories',
            'recentAttempts'
        ));
    }
}