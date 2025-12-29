<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'comments';
    
    protected $fillable = [
        'content',
        'user_id',
        'post_id',
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }
    
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', '_id');
    }
}
