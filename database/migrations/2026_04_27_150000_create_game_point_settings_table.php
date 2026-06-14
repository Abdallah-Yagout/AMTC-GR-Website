<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_point_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('profile_completed_points')->default(200);
            $table->unsignedInteger('image_uploaded_points')->default(40);
            $table->unsignedInteger('daily_claim_points')->default(10);
            $table->timestamps();
        });

        DB::table('game_point_settings')->insert([
            'id' => 1,
            'profile_completed_points' => 200,
            'image_uploaded_points' => 40,
            'daily_claim_points' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('game_point_settings');
    }
};
