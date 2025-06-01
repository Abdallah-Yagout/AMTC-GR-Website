<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale'); // 'en', 'ar', etc.
            $table->string('translatable_type'); // Model class (Post, Community, Event)
            $table->unsignedBigInteger('translatable_id'); // Model ID
            $table->string('field'); // 'title', 'body', 'description', etc.
            $table->text('value'); // The translated content

            $table->index(['translatable_type', 'translatable_id', 'locale', 'field']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
