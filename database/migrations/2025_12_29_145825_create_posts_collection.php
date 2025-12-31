<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Note: MongoDB doesn't require schema definitions like SQL databases.
     * Collections are created automatically when documents are inserted.
     * This migration is kept for consistency with Laravel's migration system.
     */
    public function up(): void
    {
        // MongoDB collections are created automatically when first document is inserted
        // No schema definition needed
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally drop the collection if needed
        // Schema::connection('mongodb')->dropIfExists('posts');
    }
};
