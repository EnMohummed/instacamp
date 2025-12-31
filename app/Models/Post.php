<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'posts';
    
    protected $fillable = [
        'caption',
        'image_path',
        'image_url',
        'user_id',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }
    
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'post_id', '_id');
    }
    
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id', '_id');
    }
    
    /**
     * Check if the post is liked by a specific user.
     */
    public function likedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        
        $userId = $user->_id ?? $user->id;
        return $this->likes()->where('user_id', $userId)->exists();
    }
}
