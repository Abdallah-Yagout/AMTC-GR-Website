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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->date('birthdate')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('gender')->nullable();
            $table->string('city')->nullable();
            $table->string('skill_level')->nullable();
            $table->boolean('has_ps5')->default(false);
            $table->string('primary_platform')->nullable();
            $table->json('regular_games')->nullable();
            $table->string('weekly_hours')->nullable();
            $table->json('favorite_games')->nullable();
            $table->string('gt7_ranking')->nullable();
            $table->string('toyota_gr_knowledge')->nullable();
            $table->text('favorite_car')->nullable();
            $table->boolean('participated_before')->default(false);
            $table->boolean('wants_training')->default(false);
            $table->boolean('join_whatsapp')->default(false);
            $table->string('heard_about')->nullable();
            $table->json('motivation')->nullable();
            $table->string('preferred_time')->nullable();
            $table->text('suggestions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
