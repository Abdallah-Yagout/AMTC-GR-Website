<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_point_settings', function (Blueprint $table) {
            $after = Schema::hasColumn('game_point_settings', 'reward_tiers') ? 'reward_tiers' : 'daily_claim_points';
            $table->unsignedInteger('check_in_day_1_points')->default(5)->after($after);
            $table->unsignedInteger('check_in_day_2_points')->default(5)->after('check_in_day_1_points');
            $table->unsignedInteger('check_in_day_3_points')->default(5)->after('check_in_day_2_points');
            $table->unsignedInteger('check_in_day_4_points')->default(5)->after('check_in_day_3_points');
            $table->unsignedInteger('check_in_day_5_points')->default(5)->after('check_in_day_4_points');
            $table->unsignedInteger('check_in_day_6_points')->default(5)->after('check_in_day_5_points');
            $table->unsignedInteger('check_in_day_7_points')->default(25)->after('check_in_day_6_points');
            $table->unsignedInteger('mission_community_rate_points')->default(5)->after('check_in_day_7_points');
            $table->unsignedInteger('mission_community_post_points')->default(10)->after('mission_community_rate_points');
            $table->unsignedInteger('mission_community_reply_points')->default(5)->after('mission_community_post_points');
            $table->unsignedInteger('mission_play_game_points')->default(30)->after('mission_community_reply_points');
            $table->unsignedInteger('mission_join_tournament_points')->default(30)->after('mission_play_game_points');
        });
    }

    public function down(): void
    {
        Schema::table('game_point_settings', function (Blueprint $table) {
            $table->dropColumn([
                'check_in_day_1_points',
                'check_in_day_2_points',
                'check_in_day_3_points',
                'check_in_day_4_points',
                'check_in_day_5_points',
                'check_in_day_6_points',
                'check_in_day_7_points',
                'mission_community_rate_points',
                'mission_community_post_points',
                'mission_community_reply_points',
                'mission_play_game_points',
                'mission_join_tournament_points',
            ]);
        });
    }
};
