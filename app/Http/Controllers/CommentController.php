<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Post $post): RedirectResponse
    {
        $data = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $post->comments()->create([
            'content' => $data['content'],
            'user_id' => auth()->id(),
            'post_id' => $post->id,
        ]);
        
        return redirect('/posts/' . $post->id)->with('success', 'Comment added successfully!');
    }

    /**
     * Show the form for editing the specified comment.
     */
    public function edit(Comment $comment): View
    {
        if (auth()->id() !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }
        return view('comments.edit', compact('comment'));
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(Request $request, Comment $comment): RedirectResponse
    {
        if (auth()->id() !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $data = $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        
        $comment->update($data);
        
        return redirect('/posts/' . $comment->post_id)->with('success', 'Comment updated successfully!');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        if (auth()->id() !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $postId = $comment->post_id;
        $comment->delete();
        
        return redirect('/posts/' . $postId)->with('success', 'Comment deleted successfully!');
    }
}
