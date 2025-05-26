<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */

    public function update(User $user, array $input): void
    {

            Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
                'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
                'birthdate' => ['nullable', 'date', 'before:today'],
                'phone' => [
                    'required',
                    'digits:9',
                    Rule::unique('users')->ignore($user->id)
                ],
                'whatsapp' => [
                    'nullable',
                    'digits:9',
                    Rule::unique('profiles')->ignore($user->id)
                ],
                'heard_about' => ['nullable', 'string', 'max:255'],
                'preferred_time' => ['nullable', 'string', 'max:255'],
                'suggestions' => ['nullable', 'string'],
                'motivation' => ['nullable', 'array'],
                'gt7_ranking' => ['nullable', 'string'],
                'favorite_games' => ['nullable', 'array'],
                'skill_level' => ['nullable', 'string'],
                'has_ps5' => ['nullable', 'boolean'],
                'primary_platform' => ['nullable', 'string'],
                'weekly_hours' => ['nullable', 'string'],
                'participated_before' => ['nullable', 'boolean'],
                'wants_training' => ['nullable', 'boolean'],
                'join_whatsapp' => ['nullable', 'boolean'],
                'toyota_gr_knowledge' => ['nullable', 'string', 'max:255'],
                'favorite_car' => ['nullable', 'string', 'max:1000'],
            ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
                'phone' => $input['phone'],
            ])->save();

            $this->updateOrCreateProfile($user, $input ?? []);
        }
    }
    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */

    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'email_verified_at' => null,
        ])->save();

        $this->updateOrCreateProfile($user, $input['profile'] ?? []);

        $user->sendEmailVerificationNotification();
    }
    protected function updateOrCreateProfile(User $user, array $profileData): void
    {
        $currentValues = $user->profile ? $user->profile->toArray() : [];

        // Merge new values over existing ones
        $mergedData = array_merge($currentValues, $profileData);

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'birthdate' => $mergedData['birthdate'] ?? null,
                'heard_about' => $mergedData['heard_about'] ?? null,
                'preferred_time' => $mergedData['preferred_time'] ?? null,
                'suggestions' => $mergedData['suggestions'] ?? null,
                'motivation' => $mergedData['motivation'] ?? [],
                'whatsapp' => $mergedData['whatsapp'] ?? null,
                'gt7_ranking' => $mergedData['gt7_ranking'] ?? null,
                'favorite_games' => $mergedData['favorite_games'] ?? [],
                'skill_level' => $mergedData['skill_level'] ?? null,
                'has_ps5' => $mergedData['has_ps5'] ?? false,
                'primary_platform' => $mergedData['primary_platform'] ?? null,
                'weekly_hours' => $mergedData['weekly_hours'] ?? null,
                'participated_before' => $mergedData['participated_before'] ?? false,
                'wants_training' => $mergedData['wants_training'] ?? false,
                'join_whatsapp' => $mergedData['join_whatsapp'] ?? false,
                'toyota_gr_knowledge' => $mergedData['toyota_gr_knowledge'] ?? null,
                'favorite_car' => $mergedData['favorite_car'] ?? null,
            ]
        );
    }


}
