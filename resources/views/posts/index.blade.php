@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            @forelse($posts as $post)
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('profile.show', $post->user) }}" class="text-decoration-none">
                                <img src="{{ $post->user->profile->profileImage() }}" 
                                     class="rounded-circle me-2" 
                                     width="30" 
                                     height="30">
                                <span class="fw-bold">{{ $post->user->username }}</span>
                            </a>
                        </div>
                        @can('delete', $post)
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Are you sure you want to delete this post?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </div>
                    
                    <img src="/storage/{{ $post->image }}" class="card-img-top" alt="Post Image">
                    
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <form action="{{ route('likes.store', $post) }}" method="POST" class="me-2">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $post->likedBy(auth()->user()) ? 'text-danger' : 'text-secondary' }}">
                                    <i class="fas fa-heart"></i> {{ $post->likes->count() }}
                                </button>
                            </form>
                            <span class="me-3">
                                <i class="far fa-comment"></i> {{ $post->comments->count() }}
                            </span>
                        </div>
                        
                        <p class="mb-1">
                            <span class="fw-bold">{{ $post->user->username }}</span> 
                            {{ $post->caption }}
                        </p>
                        
                        @if($post->comments->count() > 0)
                            <a href="{{ route('posts.show', $post) }}" class="text-decoration-none text-muted">
                                View all {{ $post->comments->count() }} comments
                            </a>
                        @endif
                        
                        <hr>
                        
                        <!-- Add Comment Form -->
                        <form action="{{ route('comments.store', $post) }}" method="POST" class="mt-2">
                            @csrf
                            <div class="input-group">
                                <input type="text" 
                                       name="content" 
                                       class="form-control" 
                                       placeholder="Add a comment..."
                                       required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="card-footer text-muted">
                        {{ $post->created_at->diffForHumans() }}
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <h4>No posts yet</h4>
                    <p class="text-muted">Follow users to see their posts or create your own!</p>
                    <a href="{{ route('posts.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Post
                    </a>
                </div>
            @endforelse
            
            <!-- Pagination -->
            @if($posts->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@auth
    @push('scripts')
    <script>
        // Add JavaScript for like/unlike without page reload using Axios
        document.addEventListener('DOMContentLoaded', function() {
            // Handle like form submission
            document.querySelectorAll('form[action*="/like"]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const form = this;
                    const url = form.action;
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update like count and button state
                            const likeButton = form.querySelector('button');
                            const likeCount = form.querySelector('.like-count');
                            
                            if (data.liked) {
                                likeButton.classList.remove('text-secondary');
                                likeButton.classList.add('text-danger');
                            } else {
                                likeButton.classList.remove('text-danger');
                                likeButton.classList.add('text-secondary');
                            }
                            
                            likeCount.textContent = data.likes_count;
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });
        });
    </script>
    @endpush
@endauth
@endsection