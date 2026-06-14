<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OAuthSetting extends Model
{
    /**
     * Without this, Laravel infers "o_auth_settings" from the class name OAuthSetting.
     */
    protected $table = 'oauth_settings';

    protected $fillable = [
        'google_client_id',
        'google_client_secret',
        'google_redirect_uri',
    ];

    protected function casts(): array
    {
        return [
            'google_client_secret' => 'encrypted',
        ];
    }

    public static function singleton(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'google_client_id' => null,
                'google_client_secret' => null,
                'google_redirect_uri' => null,
            ]
        );
    }

    /**
     * @return array{client_id: ?string, client_secret: ?string, redirect: ?string}
     */
    public static function googleConfig(): array
    {
        $row = static::query()->find(1);

        if ($row && filled($row->google_client_id) && filled($row->google_client_secret)) {
            return [
                'client_id' => $row->google_client_id,
                'client_secret' => $row->google_client_secret,
                'redirect' => filled($row->google_redirect_uri)
                    ? $row->google_redirect_uri
                    : url('/auth/google/callback'),
            ];
        }

        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirect = config('services.google.redirect');

        if (! filled($redirect) && (filled($clientId) && filled($clientSecret))) {
            $redirect = url('/auth/google/callback');
        }

        return [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect' => $redirect,
        ];
    }

    public static function isGoogleLoginConfigured(): bool
    {
        $c = self::googleConfig();

        return filled($c['client_id']) && filled($c['client_secret']) && filled($c['redirect']);
    }
}
