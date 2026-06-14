<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_user_stats', function (Blueprint $table) {
            $table->date('check_in_last_claimed_at')->nullable()->after('last_daily_claim_date');
            $table->unsignedTinyInteger('check_in_next_step')->default(1)->after('check_in_last_claimed_at');
            $table->json('mission_daily_claims_at')->nullable()->after('check_in_next_step');
            $table->timestamp('mission_play_game_awarded_at')->nullable()->after('mission_daily_claims_at');
            $table->timestamp('mission_join_tournament_awarded_at')->nullable()->after('mission_play_game_awarded_at');
        });
    }

    public function down(): void
    {
        Schema::table('game_user_stats', function (Blueprint $table) {
            $table->dropColumn([
                'check_in_last_claimed_at',
                'check_in_next_step',
                'mission_daily_claims_at',
                'mission_play_game_awarded_at',
                'mission_join_tournament_awarded_at',
            ]);
        });
    }
};
