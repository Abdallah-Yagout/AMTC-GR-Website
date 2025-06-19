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
        Schema::create('leaderboard_participant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leaderboard_id')->constrained();
            $table->foreignId('participant_id')->constrained();
            $table->integer('position');
            $table->integer('time_taken');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique(['leaderboard_id', 'participant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaderboard_participant');
    }
};
