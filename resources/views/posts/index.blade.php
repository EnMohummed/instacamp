<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @forelse($posts as $post)
                <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
                    <div class="p-4 flex justify-between items-center border-b">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('profile.show', $post->user) }}" class="flex items-center space-x-3 hover:opacity-80">
                                <img src="{{ $post->user->profile_image ? '/storage/' . $post->user->profile_image : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) }}" 
                                     class="rounded-full w-10 h-10 object-cover" 
                                     alt="{{ $post->user->name }}">
                                <span class="font-semibold text-gray-900">{{ $post->user->username ?? $post->user->name }}</span>
                            </a>
                        </div>
                        @auth
                            @php
                                $userId = auth()->id();
                                $postUserId = $post->user_id;
                            @endphp
                            @if((string)$userId === (string)$postUserId)
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 text-sm"
                                            onclick="return confirm('Are you sure you want to delete this post?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                    
                    @if($post->image_path)
                        <img src="/storage/{{ $post->image_path }}" class="w-full object-cover" alt="Post Image">
                    @endif
                    
                    <div class="p-4">
                        <div class="flex items-center space-x-4 mb-3">
                            @auth
                                <form action="{{ route('posts.like', $post) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="flex items-center space-x-1 {{ $post->likedBy(auth()->user()) ? 'text-red-600' : 'text-gray-600' }} hover:text-red-600 transition">
                                        <svg class="w-6 h-6" fill="{{ $post->likedBy(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        <span class="font-semibold">{{ $post->likes->count() }}</span>
                                    </button>
                                </form>
                            @else
                                <div class="flex items-center space-x-1 text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <span class="font-semibold">{{ $post->likes->count() }}</span>
                                </div>
                            @endauth
                            
                            <a href="{{ route('posts.show', $post) }}" class="flex items-center space-x-1 text-gray-600 hover:text-gray-900">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span class="font-semibold">{{ $post->comments->count() }}</span>
                            </a>
                        </div>
                        
                        <p class="mb-2">
                            <span class="font-semibold text-gray-900">{{ $post->user->username ?? $post->user->name }}</span> 
                            <span class="text-gray-800">{{ $post->caption }}</span>
                        </p>
                        
                        @if($post->comments->count() > 0)
                            <a href="{{ route('posts.show', $post) }}" class="text-gray-500 text-sm hover:text-gray-700">
                                View all {{ $post->comments->count() }} comments
                            </a>
                        @endif
                        
                        <div class="mt-4 pt-4 border-t">
                            @auth
                                <!-- Add Comment Form -->
                                <form action="{{ route('comments.store', $post) }}" method="POST" class="flex space-x-2">
                                    @csrf
                                    <input type="text" 
                                           name="content" 
                                           class="flex-1 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" 
                                           placeholder="Add a comment..."
                                           required>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <p class="text-gray-500 text-sm">
                                    <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800">Log in</a> to comment
                                </p>
                            @endauth
                        </div>
                    </div>
                    
                    <div class="px-4 py-2 bg-gray-50 text-gray-500 text-sm">
                        {{ $post->created_at->diffForHumans() }}
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-2">No posts yet</h3>
                    <p class="text-gray-600 mb-6">Be the first to share something amazing!</p>
                    @auth
                        <a href="{{ route('posts.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create Post
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Log in to create a post
                        </a>
                    @endauth
                </div>
            @endforelse
            
            <!-- Pagination -->
            @if($posts->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
