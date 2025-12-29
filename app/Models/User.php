<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = "mongodb";
    protected $collection = 'users'; // Changed from $table to $collection for MongoDB
    
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'profile_image',
        'bio',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function posts(): HasMany
    {
        return $this->hasMany(related: Post::class);
    }
    
    public function likes(): HasMany
    {
        return $this->hasMany(related: Like::class);
    }
    
    public function comments(): HasMany
    {
        return $this->hasMany(related: Comment::class);
    }
}
