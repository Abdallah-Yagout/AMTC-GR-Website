<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 64)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('reward_type', 32)->default('points'); // points | high_score
            $table->unsignedInteger('points_per_completion')->default(0);
            $table->unsignedSmallInteger('points_cooldown_hours')->default(24);
            $table->json('config')->nullable();
            $table->timestamps();
        });

        DB::table('game_definitions')->insert([
            'slug' => 'matching',
            'name' => 'Image Matching',
            'description' => 'Match pairs of cards. Earn Games points when you clear the board (once per cooldown).',
            'is_enabled' => true,
            'sort_order' => 0,
            'reward_type' => 'points',
            'points_per_completion' => 25,
            'points_cooldown_hours' => 24,
            'config' => json_encode(['pair_count' => 8]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('game_definitions');
    }
};
