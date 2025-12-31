@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <img src="{{ asset('storage/' . $post->image_path) }}" class="w-100" alt="Post Image">
        </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center mb-3">
                    @if($post->user)
                        <img src="{{ $post->user->profile_image ? '/storage/' . $post->user->profile_image : 'https://via.placeholder.com/40' }}" 
                        class="rounded-circle me-3" 
                        style="width: 40px; height: 40px;"
                        alt="{{ $post->user->name }}">
                        <a href="{{ route('profile.show', $post->user->id) }}" class="text-dark text-decoration-none">{{ $post->user->username ?? $post->user->name }}
                            </a>

                        @if(auth()->id() == $post->user->id)
                        <div class="dropdown ms-auto">
                            <button class="btn btn-link text-dark" type="button" id="postOptions" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="postOptions">
                                <li><a class="dropdown-item" href="{{ route('posts.edit', $post->id) }}">Edit</a></li>

                                <li>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                </form>
                                </li>
                            </ul>
                        </div>
                     @endif
                     @else
                     <img src="https://via.placeholder.com/40" class="rounded-circle me-3" style="width: 40px; height: 40px;" alt="User Image">
                     <span class="text-muted">Deleted User</span>
                     @endif
                </div>
                @if($post->user)
                <p><strong>{{ $post->user->username ?? $post->user->name }}</strong> {{ $post->caption }}</p>
                @else
                <p>{{ $post->caption }}</p>
                @endif
                <hr>
                <div class="d-flex mb-2">
                    @if($post->likes->where('user_id', auth()->id())->count() > 0)
                    <form action="{{ route('likes.destroy', $post->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link p-0 me-2">
                            <i class="fas fa-heart text-danger"></i>
                        </button>
                    </form>
                    @else
                    <form action="{{ route('likes.store', $post->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 me-2">
                            <i class="fas fa-heart text-gray-600"></i>
                        </button>
                    </form>
                    @endif
                </div>
                <p><strong>{{ $post->likes->count() }} likes</strong></p>
                <p class="text-muted">{{ $post->created_at->format('F d, Y') }}</p>
                <hr>
                <div class="comments-section " style="max-height: 300px; overflow-y: auto;">
                    @foreach($post->comments as $comment)
                    <div class="d-flex mb-2">
                        @if($comment->user)
                        <strong class="me-2">{{ $comment->user->username ?? $comment->user->name }}</strong>
                        @else
                        <strong class="me-2">Deleted User</strong>
                        @endif
                        <span class="mb-0">{{ $comment->comment }}</span>
                        @if($comment->user && (auth()->id() == $comment->user->id || auth()->id() == $post->user->id))
                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link p-0 text-danger">
                                <i class="fas fa-trash text-danger"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                    @endforeach
                </div>
                <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mb-4">
                    @csrf
                    <input type="text" name="comment" class="form-control" placeholder="Add a comment..." required>
                    <button type="submit" class="btn btn-primary mt-2">Post</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
