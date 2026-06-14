<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_user_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('game_slug', 64);
            $table->timestamp('last_points_awarded_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'game_slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_user_awards');
    }
};
