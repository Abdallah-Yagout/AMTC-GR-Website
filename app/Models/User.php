<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;


class User extends Authenticatable implements FilamentUser,\Illuminate\Contracts\Auth\MustVerifyEmail,CanResetPassword
{
    use HasApiTokens;
    use SoftDeletes;


    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'type',
    ];
    public function upvotes()
    {
        return $this->hasMany(Vote::class);
    }
    public function toggleUpvote($model)
    {
        $upvote = $this->upvotes()
            ->where('upvoteable_type', get_class($model))
            ->where('upvoteable_id', $model->id)
            ->first();

        if ($upvote) {
            $upvote->delete();
            return false;
        }

        $this->upvotes()->create([
            'upvoteable_type' => get_class($model),
            'upvoteable_id' => $model->id,
        ]);

        return true;
    }
    // app/Models/User.php
    public function getAvatarUrlAttribute()
    {
        // If profile photo path exists
        if ($this->profile_photo_path) {
            $storagePath = 'storage/'.$this->profile_photo_path;
            $fullPath = public_path($storagePath);

            // Check if file actually exists
            if (file_exists($fullPath)) {
                return asset($storagePath);
            }

            // File doesn't exist - fall through to avatar generation
        }

        // Generate UI avatar as fallback
        return 'https://ui-avatars.com/api/?' . http_build_query([
                'name' => urlencode($this->name),
                'background' => '6366f1', // indigo-500
                'color' => 'fff',
                'size' => 256,
                'length' => 2, // Only use first 2 letters
                'rounded' => true,
                'bold' => true,
                'format' => 'png'
            ]);
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];
// In User.php
    public function leaderboards()
    {
        return $this->hasMany(Leaderboard::class);
    }
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'favorite_games' => 'array',
            'motivation' => 'array',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return auth()->user()->type==1;
    }


}
