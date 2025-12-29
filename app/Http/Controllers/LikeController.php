<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new like for a post.
     */
    public function store(Post $post)
    {
        // Check if user already liked the post
        if ($post->likes()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'You have already liked this post.');
        }

        $post->likes()->create([
            'user_id' => auth()->id()
        ]);
        
        return back()->with('success', 'Post liked!');
    }

    /**
     * Remove a like from a post.
     */
    public function destroy(Post $post)
    {
        $deleted = $post->likes()
            ->where('user_id', auth()->id())
            ->delete();
            
        if ($deleted) {
            return back()->with('success', 'Post unliked!');
        }
        
        return back()->with('error', 'Like not found.');
    }
   
}
