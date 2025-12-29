<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page with recent posts.
     */
    public function index(): View
    {
        $posts = Post::with(['user', 'likes', 'comments'])
            ->latest()
            ->paginate(10);
            
        return view('home', compact('posts'));
    }

    /**
     * Show the application dashboard.
     */
    public function dashboard(): View
    {
        $user = auth()->user();
        $posts = Post::where('user_id', $user->id)
            ->with(['likes', 'comments'])
            ->latest()
            ->paginate(10);
            
        return view('dashboard', compact('posts'));
    }
}
