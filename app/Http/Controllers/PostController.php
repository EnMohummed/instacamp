<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $posts = Post::with('user')->latest()->get();
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'caption' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $imagePath = $request->file('image')->store('uploads', 'public');
        auth()->user()->posts()->create([
            'caption' => $data['caption'],
            'image_path' => $imagePath,
        ]);
        
        return redirect()->route('profile.show', auth()->user());
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): View
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): View
    {
        $userId = auth()->id();
        $postUserId = $post->user_id;
        
        // Handle MongoDB ObjectId comparison
        if ((string)$userId !== (string)$postUserId) {
            abort(403, 'Unauthorized action.');
        }
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        $userId = auth()->id();
        $postUserId = $post->user_id;
        
        // Handle MongoDB ObjectId comparison
        if ((string)$userId !== (string)$postUserId) {
            abort(403, 'Unauthorized action.');
        }
        $data = $request->validate([
            'caption' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            // Store new image
            $imagePath = $request->file('image')->store('uploads', 'public');
            $data['image_path'] = $imagePath;
        }
        
        $post->update($data);
        return redirect()->route('posts.show', $post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        $userId = auth()->id();
        $postUserId = $post->user_id;
        
        // Handle MongoDB ObjectId comparison
        if ((string)$userId !== (string)$postUserId) {
            abort(403, 'Unauthorized action.');
        }

        Storage::disk('public')->delete($post->image_path);
        $post->delete();
        return redirect()->route('profile.show', auth()->user());
    }
}
