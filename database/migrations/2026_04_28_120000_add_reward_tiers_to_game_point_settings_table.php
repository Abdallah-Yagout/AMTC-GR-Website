<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_point_settings', function (Blueprint $table) {
            $table->json('reward_tiers')->nullable()->after('daily_claim_points');
        });
    }

    public function down(): void
    {
        Schema::table('game_point_settings', function (Blueprint $table) {
            $table->dropColumn('reward_tiers');
        });
    }
};
