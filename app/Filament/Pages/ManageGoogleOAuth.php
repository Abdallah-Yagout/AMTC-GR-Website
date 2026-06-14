<?php

namespace App\Filament\Pages;

use App\Models\OAuthSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Support\Enums\Alignment;

class ManageGoogleOAuth extends Page
{
    use InteractsWithFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static string $view = 'filament.pages.manage-google-oauth';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Google login';

    protected static ?string $title = 'Google login';

    protected static ?int $navigationSort = 99;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $settings = OAuthSetting::singleton();

        $this->form->fill([
            'google_client_id' => $settings->google_client_id,
            'google_client_secret' => '',
            'google_redirect_uri' => $settings->google_redirect_uri ?: url('/auth/google/callback'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('google_client_id')
                    ->label(__('Client ID'))
                    ->maxLength(500),
                TextInput::make('google_client_secret')
                    ->label(__('Client secret'))
                    ->password()
                    ->revealable(filament()->arePasswordsRevealable())
                    ->helperText(__('Leave blank to keep the current secret unchanged.')),
                TextInput::make('google_redirect_uri')
                    ->label(__('Redirect URI'))
                    ->maxLength(2048)
                    ->helperText(__('Must match an authorized redirect URI in Google Cloud Console (e.g. :url).', ['url' => url('/auth/google/callback')])),
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
        $settings = OAuthSetting::singleton();

        $settings->google_client_id = filled($state['google_client_id'] ?? null)
            ? $state['google_client_id']
            : null;

        if (filled($state['google_client_secret'] ?? null)) {
            $settings->google_client_secret = $state['google_client_secret'];
        }

        $settings->google_redirect_uri = filled($state['google_redirect_uri'] ?? null)
            ? $state['google_redirect_uri']
            : null;

        $settings->save();

        Notification::make()
            ->title(__('Saved'))
            ->success()
            ->send();

        $this->form->fill([
            'google_client_id' => $settings->google_client_id,
            'google_client_secret' => '',
            'google_redirect_uri' => $settings->google_redirect_uri ?: url('/auth/google/callback'),
        ]);
    }
}
