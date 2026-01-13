<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;


//set homepage to show posts feed (required authentication)
Route::get('/', [PostController::class, 'index'])
    ->middleware('auth')
    ->name('home'); 

    Route::get('/home',function (): RedirectResponse {
        return redirect('/');
    })->name('home');

    
    Auth::routes();

  //Post Routes
  Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
  Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
  Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
  Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
  Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
  Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
  Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

  //Comment Routes
  Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
  Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
  Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
  Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

  //Like Routes
  Route::post('/posts/{post}/like', [LikeController::class, 'store'])->name('posts.like');
  Route::delete('/posts/{post}/unlike', [LikeController::class, 'destroy'])->name('posts.unlike');

  //Profile Routes
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');