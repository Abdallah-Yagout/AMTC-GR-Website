<?php

namespace App\Services;

use App\Models\GamePointSetting;
use App\Models\GameUserStat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GamePointsService
{
    public const PROFILE_COMPLETED_POINTS = 200;

    public const IMAGE_UPLOADED_POINTS = 40;

    public const DAILY_CLAIM_POINTS = 10;

    public function profileCompletedAmount(): int
    {
        return (int) GamePointSetting::singleton()->profile_completed_points;
    }

    public function imageUploadedAmount(): int
    {
        return (int) GamePointSetting::singleton()->image_uploaded_points;
    }

    public function dailyClaimAmount(): int
    {
        return (int) GamePointSetting::singleton()->daily_claim_points;
    }

    /**
     * @return array{profile_complete: int, image_upload: int, daily_claim: int}
     */
    public function rewardAmounts(): array
    {
        $s = GamePointSetting::singleton();

        return [
            'profile_complete' => (int) $s->profile_completed_points,
            'image_upload' => (int) $s->image_uploaded_points,
            'daily_claim' => (int) $s->daily_claim_points,
        ];
    }

    public function getOrCreateStat(User $user): GameUserStat
    {
        return GameUserStat::firstOrCreate(
            ['user_id' => $user->id],
            ['points' => 0]
        );
    }

    public function awardProfileCompleted(User $user): bool
    {
        $profile = $user->profile;
        if (! $profile || ! $profile->isCompleteForRace()) {
            return false;
        }

        $amount = $this->profileCompletedAmount();

        return DB::transaction(function () use ($user, $amount): bool {
            $stat = GameUserStat::query()->lockForUpdate()->firstOrCreate(
                ['user_id' => $user->id],
                ['points' => 0]
            );

            if ($stat->profile_completed_awarded_at) {
                return false;
            }

            $stat->points += $amount;
            $stat->profile_completed_awarded_at = now();
            $stat->save();

            return true;
        });
    }

    public function awardImageUploaded(User $user): bool
    {
        if (! filled($user->profile_photo_path)) {
            return false;
        }

        $amount = $this->imageUploadedAmount();

        return DB::transaction(function () use ($user, $amount): bool {
            $stat = GameUserStat::query()->lockForUpdate()->firstOrCreate(
                ['user_id' => $user->id],
                ['points' => 0]
            );

            if ($stat->image_uploaded_awarded_at) {
                return false;
            }

            $stat->points += $amount;
            $stat->image_uploaded_awarded_at = now();
            $stat->save();

            return true;
        });
    }

    /**
     * @return array{awarded: bool, points: int, already_claimed: bool}
     */
    public function claimDaily(User $user): array
    {
        $amount = $this->dailyClaimAmount();

        return DB::transaction(function () use ($user, $amount): array {
            $today = Carbon::today()->toDateString();
            $stat = GameUserStat::query()->lockForUpdate()->firstOrCreate(
                ['user_id' => $user->id],
                ['points' => 0]
            );

            if ($stat->last_daily_claim_date && $stat->last_daily_claim_date->toDateString() === $today) {
                return [
                    'awarded' => false,
                    'points' => $stat->points,
                    'already_claimed' => true,
                ];
            }

            $stat->points += $amount;
            $stat->last_daily_claim_date = $today;
            $stat->save();

            return [
                'awarded' => true,
                'points' => $stat->points,
                'already_claimed' => false,
            ];
        });
    }
}
