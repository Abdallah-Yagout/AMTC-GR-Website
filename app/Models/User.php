<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements \Illuminate\Contracts\Auth\MustVerifyEmail, CanResetPassword, FilamentUser
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use Notifiable;
    use SoftDeletes;
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
        'google_id',
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
        return 'https://ui-avatars.com/api/?'.http_build_query([
            'name' => urlencode($this->name),
            'background' => '6366f1', // indigo-500
            'color' => 'fff',
            'size' => 256,
            'length' => 2, // Only use first 2 letters
            'rounded' => true,
            'bold' => true,
            'format' => 'png',
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

    protected function profilePhotoUrl(): Attribute
    {
        return Attribute::get(function (): string {
            if (filled($this->profile_photo_path)) {
                $diskName = $this->profilePhotoDisk();
                $disk = Storage::disk($diskName);
                if ($disk->exists($this->profile_photo_path)) {
                    $driver = config("filesystems.disks.{$diskName}.driver");
                    if ($driver === 'local') {
                        $normalized = ltrim(str_replace('\\', '/', $this->profile_photo_path), '/');

                        return '/storage/'.$normalized;
                    }

                    return $disk->url($this->profile_photo_path);
                }
            }

            return $this->localDefaultProfilePhotoUrl();
        });
    }

    /** SVG data URL for avatars when upload is missing or fails to load in the browser. */
    public function profilePhotoFallbackUrl(): string
    {
        return $this->localDefaultProfilePhotoUrl();
    }

    protected function localDefaultProfilePhotoUrl(): string
    {
        $initials = collect(explode(' ', (string) $this->name))
            ->filter()
            ->map(fn (string $segment) => mb_substr($segment, 0, 1))
            ->take(2)
            ->join(' ');

        if ($initials === '') {
            $initials = 'GR';
        }

        $initialsSafe = htmlspecialchars($initials, ENT_XML1 | ENT_QUOTES, 'UTF-8');

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="256" height="256" viewBox="0 0 256 256">
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#5a000b"/>
      <stop offset="100%" stop-color="#e60010"/>
    </linearGradient>
  </defs>
  <rect width="256" height="256" rx="128" fill="url(#bg)"/>
  <text x="50%" y="54%" text-anchor="middle" fill="#ffffff" font-size="88" font-family="Arial, sans-serif" font-weight="700">{$initialsSafe}</text>
</svg>
SVG;

        return 'data:image/svg+xml;utf8,'.rawurlencode($svg);
    }

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

    public function gameUserStat()
    {
        return $this->hasOne(GameUserStat::class);
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
        return auth()->user()->type == 1;
    }
}
