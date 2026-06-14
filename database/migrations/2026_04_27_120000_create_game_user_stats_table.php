<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_user_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('points')->default(0);
            $table->timestamp('profile_completed_awarded_at')->nullable();
            $table->timestamp('image_uploaded_awarded_at')->nullable();
            $table->date('last_daily_claim_date')->nullable();
            $table->timestamps();

            $table->index('points');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_user_stats');
    }
};
