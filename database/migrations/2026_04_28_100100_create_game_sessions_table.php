<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('game_slug', 64);
            $table->string('state', 32)->default('pending'); // pending | completed | expired
            $table->json('payload');
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index(['user_id', 'game_slug', 'state']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};
