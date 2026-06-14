<?php

namespace App\Filament\Pages;

use App\Models\GamePointSetting;
use App\Support\GamePointsTierProgress;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Support\Enums\Alignment;
use InvalidArgumentException;

class ManagePointSystem extends Page
{
    use InteractsWithFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static string $view = 'filament.pages.manage-point-system';

    protected static ?string $navigationGroup = 'Point System';

    protected static ?string $navigationLabel = 'Reward amounts';

    protected static ?string $title = 'Games point rewards';

    protected static ?int $navigationSort = 1;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $s = GamePointSetting::singleton();

        $this->form->fill([
            'profile_completed_points' => $s->profile_completed_points,
            'image_uploaded_points' => $s->image_uploaded_points,
            'daily_claim_points' => $s->daily_claim_points,
            'reward_tiers' => $s->rewardTiersOrConfigDefault(),
            'check_in_day_1_points' => $s->check_in_day_1_points,
            'check_in_day_2_points' => $s->check_in_day_2_points,
            'check_in_day_3_points' => $s->check_in_day_3_points,
            'check_in_day_4_points' => $s->check_in_day_4_points,
            'check_in_day_5_points' => $s->check_in_day_5_points,
            'check_in_day_6_points' => $s->check_in_day_6_points,
            'check_in_day_7_points' => $s->check_in_day_7_points,
            'mission_community_rate_points' => $s->mission_community_rate_points,
            'mission_community_post_points' => $s->mission_community_post_points,
            'mission_community_reply_points' => $s->mission_community_reply_points,
            'mission_play_game_points' => $s->mission_play_game_points,
            'mission_join_tournament_points' => $s->mission_join_tournament_points,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Point grants'))
                    ->description(__('One-time and daily point awards.'))
                    ->schema([
                        TextInput::make('profile_completed_points')
                            ->label(__('Points for complete profile (one-time)'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(999999)
                            ->required(),
                        TextInput::make('image_uploaded_points')
                            ->label(__('Points for profile photo upload (one-time)'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(999999)
                            ->required(),
                        TextInput::make('daily_claim_points')
                            ->label(__('Points per daily claim on Games page'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(999999)
                            ->required(),
                    ])
                    ->columns(1),
                Section::make(__('Profile reward path (8 tiers)'))
                    ->description(__('Thresholds and labels for the tier track on the driver profile. The first tier must stay at 0 points; each row must have higher min points than the previous.'))
                    ->schema([
                        Repeater::make('reward_tiers')
                            ->label(__('Tiers'))
                            ->schema([
                                TextInput::make('min_points')
                                    ->label(__('Min points'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(99999999)
                                    ->required(),
                                TextInput::make('label')
                                    ->label(__('Tier name'))
                                    ->required()
                                    ->maxLength(120),
                                Select::make('badge')
                                    ->label(__('Badge style'))
                                    ->options(config('game_reward_tiers.badge_options', []))
                                    ->required()
                                    ->native(false),
                            ])
                            ->minItems(8)
                            ->maxItems(8)
                            ->defaultItems(8)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->columns(3),
                    ]),
                Section::make(__('Profile check-in ladder (7 days)'))
                    ->description(__('Points per consecutive check-in day on the driver profile. Missing a calendar day resets progress to day 1.'))
                    ->schema([
                        TextInput::make('check_in_day_1_points')->label(__('Day 1'))->numeric()->minValue(0)->required(),
                        TextInput::make('check_in_day_2_points')->label(__('Day 2'))->numeric()->minValue(0)->required(),
                        TextInput::make('check_in_day_3_points')->label(__('Day 3'))->numeric()->minValue(0)->required(),
                        TextInput::make('check_in_day_4_points')->label(__('Day 4'))->numeric()->minValue(0)->required(),
                        TextInput::make('check_in_day_5_points')->label(__('Day 5'))->numeric()->minValue(0)->required(),
                        TextInput::make('check_in_day_6_points')->label(__('Day 6'))->numeric()->minValue(0)->required(),
                        TextInput::make('check_in_day_7_points')->label(__('Day 7 (bonus)'))->numeric()->minValue(0)->required(),
                    ])
                    ->columns(3),
                Section::make(__('Community and mission rewards'))
                    ->description(__('Daily missions reset each day; one-time missions award once per account.'))
                    ->schema([
                        TextInput::make('mission_community_rate_points')
                            ->label(__('Forum upvote / rate (daily)'))
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                        TextInput::make('mission_community_post_points')
                            ->label(__('New forum post (daily)'))
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                        TextInput::make('mission_community_reply_points')
                            ->label(__('Forum reply (daily)'))
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                        TextInput::make('mission_play_game_points')
                            ->label(__('First game points win (one-time)'))
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                        TextInput::make('mission_join_tournament_points')
                            ->label(__('First tournament join (one-time)'))
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ])
                    ->columns(1),
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
        $s = GamePointSetting::singleton();

        try {
            GamePointsTierProgress::assertValidTierRows($state['reward_tiers'] ?? []);
        } catch (InvalidArgumentException $e) {
            Notification::make()
                ->title($e->getMessage())
                ->danger()
                ->send();

            return;
        }

        $s->profile_completed_points = max(0, (int) ($state['profile_completed_points'] ?? 0));
        $s->image_uploaded_points = max(0, (int) ($state['image_uploaded_points'] ?? 0));
        $s->daily_claim_points = max(0, (int) ($state['daily_claim_points'] ?? 0));
        $s->reward_tiers = array_values($state['reward_tiers'] ?? []);
        $s->check_in_day_1_points = max(0, (int) ($state['check_in_day_1_points'] ?? 0));
        $s->check_in_day_2_points = max(0, (int) ($state['check_in_day_2_points'] ?? 0));
        $s->check_in_day_3_points = max(0, (int) ($state['check_in_day_3_points'] ?? 0));
        $s->check_in_day_4_points = max(0, (int) ($state['check_in_day_4_points'] ?? 0));
        $s->check_in_day_5_points = max(0, (int) ($state['check_in_day_5_points'] ?? 0));
        $s->check_in_day_6_points = max(0, (int) ($state['check_in_day_6_points'] ?? 0));
        $s->check_in_day_7_points = max(0, (int) ($state['check_in_day_7_points'] ?? 0));
        $s->mission_community_rate_points = max(0, (int) ($state['mission_community_rate_points'] ?? 0));
        $s->mission_community_post_points = max(0, (int) ($state['mission_community_post_points'] ?? 0));
        $s->mission_community_reply_points = max(0, (int) ($state['mission_community_reply_points'] ?? 0));
        $s->mission_play_game_points = max(0, (int) ($state['mission_play_game_points'] ?? 0));
        $s->mission_join_tournament_points = max(0, (int) ($state['mission_join_tournament_points'] ?? 0));
        $s->save();

        Notification::make()
            ->title(__('Saved'))
            ->success()
            ->send();

        $this->form->fill([
            'profile_completed_points' => $s->profile_completed_points,
            'image_uploaded_points' => $s->image_uploaded_points,
            'daily_claim_points' => $s->daily_claim_points,
            'reward_tiers' => $s->rewardTiersOrConfigDefault(),
            'check_in_day_1_points' => $s->check_in_day_1_points,
            'check_in_day_2_points' => $s->check_in_day_2_points,
            'check_in_day_3_points' => $s->check_in_day_3_points,
            'check_in_day_4_points' => $s->check_in_day_4_points,
            'check_in_day_5_points' => $s->check_in_day_5_points,
            'check_in_day_6_points' => $s->check_in_day_6_points,
            'check_in_day_7_points' => $s->check_in_day_7_points,
            'mission_community_rate_points' => $s->mission_community_rate_points,
            'mission_community_post_points' => $s->mission_community_post_points,
            'mission_community_reply_points' => $s->mission_community_reply_points,
            'mission_play_game_points' => $s->mission_play_game_points,
            'mission_join_tournament_points' => $s->mission_join_tournament_points,
        ]);
    }
}
