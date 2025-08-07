<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;
use App\Http\Controllers\Admin\QuestionController;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

Auth::routes(['register' => true]);

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Masyarakat Dashboard - hanya untuk role masyarakat
Route::middleware(['auth', 'role:masyarakat'])->group(function () {
    Route::get('/masyarakat/dashboard', function () {
        return view('dashboards.masyarakat');
    })->name('masyarakat.dashboard');
});

// Pelajar Dashboard - hanya untuk role pelajar
Route::middleware(['auth', 'role:pelajar'])->group(function () {
    Route::get('/pelajar/dashboard', function () {
        return view('dashboards.pelajar');
    })->name('pelajar.dashboard');
});

// Quiz Routes
Route::middleware('auth')->group(function () {
    Route::get('/quiz/{quiz}', [QuizController::class, 'show'])->name('quiz.show');
    Route::post('/quiz/{quiz}/start', [QuizController::class, 'start'])->name('quiz.start');
    Route::get('/quiz/{quiz}/take/{attempt}', [QuizController::class, 'take'])->name('quiz.take');
    Route::post('/quiz/{quiz}/submit/{attempt}', [QuizController::class, 'submit'])->name('quiz.submit');

    // Results
    Route::get('/results', [ResultController::class, 'index'])->name('results.index');
    Route::get('/result/{attempt}', [ResultController::class, 'show'])->name('quiz.result');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('quizzes', AdminQuizController::class);

    Route::get('/quizzes/{quiz}/questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::get('/quizzes/{quiz}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/quizzes/{quiz}/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('/quizzes/{quiz}/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/quizzes/{quiz}/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/quizzes/{quiz}/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
});
