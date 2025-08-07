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

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'masyarakat') {
            return redirect()->route('masyarakat.dashboard');
        }

        if ($user->role === 'pelajar') {
            return redirect()->route('pelajar.dashboard');
        }

        return redirect('/'); // fallback
    }
}
