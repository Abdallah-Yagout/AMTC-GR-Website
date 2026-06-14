<?php

namespace App\Filament\Pages;

use App\Models\MailSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ManageMailSettings extends Page
{
    use InteractsWithFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static string $view = 'filament.pages.manage-mail-settings';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Email';

    protected static ?string $title = 'Email settings';

    protected static ?int $navigationSort = 98;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $settings = MailSetting::singleton();

        $this->form->fill([
            'mail_mailer' => $settings->mail_mailer ?: config('mail.default', 'log'),
            'smtp_host' => $settings->smtp_host ?: config('mail.mailers.smtp.host'),
            'smtp_port' => $settings->smtp_port ?: config('mail.mailers.smtp.port', 587),
            'smtp_username' => $settings->smtp_username ?: config('mail.mailers.smtp.username'),
            'smtp_password' => '',
            'smtp_encryption' => $settings->smtp_encryption ?: $this->defaultEncryptionFromConfig(),
            'from_address' => $settings->from_address ?: config('mail.from.address'),
            'from_name' => $settings->from_name ?: config('mail.from.name'),
            'test_email' => auth()->user()?->email,
        ]);
    }

    private function defaultEncryptionFromConfig(): string
    {
        if (config('mail.mailers.smtp.scheme') === 'smtps' || (int) config('mail.mailers.smtp.port') === 465) {
            return 'ssl';
        }

        return 'tls';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Delivery'))
                    ->schema([
                        Select::make('mail_mailer')
                            ->label(__('Mail driver'))
                            ->options([
                                'smtp' => 'SMTP',
                                'log' => __('Log (development)'),
                                'sendmail' => 'Sendmail',
                            ])
                            ->required()
                            ->live(),
                        Select::make('smtp_encryption')
                            ->label(__('Encryption'))
                            ->options([
                                'tls' => 'TLS (port 587)',
                                'ssl' => 'SSL (port 465)',
                                'none' => __('None'),
                            ])
                            ->default('tls')
                            ->visible(fn (Get $get): bool => $get('mail_mailer') === 'smtp'),
                        TextInput::make('smtp_host')
                            ->label(__('SMTP host'))
                            ->maxLength(255)
                            ->required(fn (Get $get): bool => $get('mail_mailer') === 'smtp')
                            ->visible(fn (Get $get): bool => $get('mail_mailer') === 'smtp'),
                        TextInput::make('smtp_port')
                            ->label(__('SMTP port'))
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(65535)
                            ->default(587)
                            ->required(fn (Get $get): bool => $get('mail_mailer') === 'smtp')
                            ->visible(fn (Get $get): bool => $get('mail_mailer') === 'smtp'),
                        TextInput::make('smtp_username')
                            ->label(__('SMTP username'))
                            ->maxLength(255)
                            ->visible(fn (Get $get): bool => $get('mail_mailer') === 'smtp'),
                        TextInput::make('smtp_password')
                            ->label(__('SMTP password'))
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->helperText(__('Leave blank to keep the current password unchanged.'))
                            ->visible(fn (Get $get): bool => $get('mail_mailer') === 'smtp'),
                    ]),
                Section::make(__('Sender'))
                    ->schema([
                        TextInput::make('from_address')
                            ->label(__('From email address'))
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('from_name')
                            ->label(__('From name'))
                            ->maxLength(255),
                    ]),
                Section::make(__('Test'))
                    ->schema([
                        TextInput::make('test_email')
                            ->label(__('Send test email to'))
                            ->email()
                            ->maxLength(255)
                            ->helperText(__('Uses the values in the form above (save to persist for verification emails and other app mail).')),
                    ]),
            ])
            ->statePath('data');
    }

    /**
     * @return array<Action | \Filament\Actions\ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('Save'))
                ->submit('save')
                ->keyBindings(['mod+s']),
            Action::make('sendTestEmail')
                ->label(__('Send test email'))
                ->color('gray')
                ->action('sendTestEmail'),
        ];
    }

    protected function hasFullWidthFormActions(): bool
    {
        return false;
    }

    public function getFormActionsAlignment(): string|Alignment
    {
        return Alignment::Start;
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $settings = MailSetting::singleton();

        $settings->mail_mailer = filled($state['mail_mailer'] ?? null)
            ? $state['mail_mailer']
            : null;
        $settings->smtp_host = filled($state['smtp_host'] ?? null) ? $state['smtp_host'] : null;
        $settings->smtp_port = filled($state['smtp_port'] ?? null) ? (int) $state['smtp_port'] : null;
        $settings->smtp_username = filled($state['smtp_username'] ?? null) ? $state['smtp_username'] : null;

        if (filled($state['smtp_password'] ?? null)) {
            $settings->smtp_password = $state['smtp_password'];
        }

        $settings->smtp_encryption = filled($state['smtp_encryption'] ?? null)
            ? $state['smtp_encryption']
            : null;
        $settings->from_address = filled($state['from_address'] ?? null) ? $state['from_address'] : null;
        $settings->from_name = filled($state['from_name'] ?? null) ? $state['from_name'] : null;

        $settings->save();
        MailSetting::applyConfig();

        Notification::make()
            ->title(__('Saved'))
            ->success()
            ->send();

        $this->form->fill([
            'mail_mailer' => $settings->mail_mailer,
            'smtp_host' => $settings->smtp_host,
            'smtp_port' => $settings->smtp_port,
            'smtp_username' => $settings->smtp_username,
            'smtp_password' => '',
            'smtp_encryption' => $settings->smtp_encryption ?: $this->defaultEncryptionFromConfig(),
            'from_address' => $settings->from_address,
            'from_name' => $settings->from_name,
            'test_email' => $state['test_email'] ?? auth()->user()?->email,
        ]);
    }

    public function sendTestEmail(): void
    {
        $state = $this->form->getState();
        $recipient = $state['test_email'] ?? null;

        if (! filled($recipient)) {
            Notification::make()
                ->title(__('Enter a test email address.'))
                ->danger()
                ->send();

            return;
        }

        $this->applyFormStateToMailConfig($state);
        Mail::purge($state['mail_mailer'] ?? config('mail.default'));

        try {
            Mail::raw(
                __('This is a test email from :app.', ['app' => config('app.name')]),
                function ($message) use ($recipient) {
                    $message->to($recipient)
                        ->subject(__('Test email from :app', ['app' => config('app.name')]));
                }
            );

            Notification::make()
                ->title(__('Test email sent'))
                ->body(__('Check the inbox for :email.', ['email' => $recipient]))
                ->success()
                ->send();
        } catch (Throwable $e) {
            report($e);

            Notification::make()
                ->title(__('Could not send test email'))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * @param  array<string, mixed>  $state
     */
    private function applyFormStateToMailConfig(array $state): void
    {
        $mailer = $state['mail_mailer'] ?? config('mail.default', 'log');

        Config::set('mail.default', $mailer);

        if ($mailer === 'smtp') {
            $settings = MailSetting::singleton();

            Config::set('mail.mailers.smtp.host', $state['smtp_host'] ?? '127.0.0.1');
            Config::set('mail.mailers.smtp.port', (int) ($state['smtp_port'] ?? 587));
            Config::set('mail.mailers.smtp.username', $state['smtp_username'] ?? null);
            Config::set('mail.mailers.smtp.password', filled($state['smtp_password'] ?? null)
                ? $state['smtp_password']
                : $settings->smtp_password);
            Config::set('mail.mailers.smtp.scheme', match ($state['smtp_encryption'] ?? 'tls') {
                'ssl' => 'smtps',
                default => null,
            });
            Config::set('mail.mailers.smtp.url', null);
        }

        if (filled($state['from_address'] ?? null)) {
            Config::set('mail.from.address', $state['from_address']);
        }

        if (filled($state['from_name'] ?? null)) {
            Config::set('mail.from.name', $state['from_name']);
        }
    }
}
