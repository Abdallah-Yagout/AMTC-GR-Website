<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class MailSetting extends Model
{
    protected $fillable = [
        'mail_mailer',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'from_address',
        'from_name',
    ];

    protected function casts(): array
    {
        return [
            'smtp_port' => 'integer',
            'smtp_password' => 'encrypted',
        ];
    }

    public static function singleton(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'mail_mailer' => null,
                'smtp_host' => null,
                'smtp_port' => null,
                'smtp_username' => null,
                'smtp_password' => null,
                'smtp_encryption' => null,
                'from_address' => null,
                'from_name' => null,
            ]
        );
    }

    public static function isConfigured(): bool
    {
        $row = static::query()->find(1);

        if (! $row || ! filled($row->mail_mailer)) {
            return false;
        }

        if ($row->mail_mailer === 'smtp') {
            return filled($row->smtp_host) && filled($row->from_address);
        }

        return filled($row->from_address);
    }

    public static function applyConfig(): void
    {
        if (! Schema::hasTable('mail_settings')) {
            return;
        }

        $row = static::query()->find(1);

        if (! $row || ! filled($row->mail_mailer)) {
            return;
        }

        Config::set('mail.default', $row->mail_mailer);

        if ($row->mail_mailer === 'smtp') {
            Config::set('mail.mailers.smtp.host', $row->smtp_host ?? '127.0.0.1');
            Config::set('mail.mailers.smtp.port', $row->smtp_port ?? 587);
            Config::set('mail.mailers.smtp.username', $row->smtp_username);
            Config::set('mail.mailers.smtp.password', $row->smtp_password);
            Config::set('mail.mailers.smtp.scheme', self::schemeForEncryption($row->smtp_encryption));
            Config::set('mail.mailers.smtp.url', null);
        }

        if (filled($row->from_address)) {
            Config::set('mail.from.address', $row->from_address);
        }

        if (filled($row->from_name)) {
            Config::set('mail.from.name', $row->from_name);
        }
    }

    private static function schemeForEncryption(?string $encryption): ?string
    {
        return match ($encryption) {
            'ssl' => 'smtps',
            default => null,
        };
    }
}
