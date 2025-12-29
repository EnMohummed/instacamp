<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint as MongoBlueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (MongoBlueprint $collection) {
            $collection->index('user_id');
            $collection->index('created_at');
            $collection->index('likes_count');
            $collection->index('comments_count');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
