<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParticipantResource\Pages;
use App\Filament\Resources\ParticipantResource\RelationManagers;
use App\Helpers\Location;
use App\Models\Leaderboard;
use App\Models\Participant;
use App\Models\Tournament;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class ParticipantResource extends Resource
{
    protected static ?string $model = Participant::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

//    public static function getRecordRouteKeyName(): string
//    {
//        return 'user_id';
//    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Tournament Assignment')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->searchable()
                            ->options(\App\Models\User::all()->pluck('name', 'id'))
                            ->required(),

                        Forms\Components\Select::make('tournament_id')
                            ->label('Tournament')
                            ->searchable()
                            ->options(\App\Models\Tournament::all()->pluck('title', 'id'))
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('location', null)),

                        Forms\Components\Select::make('location')
                            ->label('Location')
                            ->options(function (callable $get) {
                                $tournamentId = $get('tournament_id');
                                if (!$tournamentId) return [];

                                $tournament = \App\Models\Tournament::find($tournamentId);
                                $locations = $tournament?->location ?? '[]';


                                if (!is_array($locations)) return [];

                                return collect($locations)
                                    ->filter(fn ($loc) => filled($loc)) // remove null/empty strings
                                    ->mapWithKeys(fn ($loc) => [(string) $loc => (string) $loc])
                                    ->toArray();
                            })
                            ->required()
//                            ->disabled(fn (callable $get) => $get('tournament_id') === null)
                            ->reactive()
                    ])
                    ->columns(3)
                    ->visible(fn (string $operation): bool => $operation === 'create'),

                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Section::make('Basic Information')
                            ->relationship('user')
                            ->schema([
                                Forms\Components\FileUpload::make('profile_photo_path')
                                    ->image()
                                    ->avatar()
                                    ->directory('gt-cup-profiles')
                                    ->columnSpanFull()
                                    ->visible(fn (string $operation): bool => $operation !== 'create')
                                    ->disabled(fn (string $operation): bool =>  $operation === 'view'),
                                Forms\Components\TextInput::make('name')
                                    ->label('Full Name')
                                    ->required()
                                    ->columnSpan(1)
                                    ->disabled(fn (string $operation): bool =>  $operation === 'view'),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->columnSpan(1)
                                    ->disabled(fn (string $operation): bool =>  $operation === 'view'),

                                Forms\Components\Fieldset::make('birthdate')
                                    ->label(null)
                                    ->relationship('profile')
                                    ->extraAttributes(['class' => 'border-none p-0'])
                                    ->schema([
                                        Forms\Components\DatePicker::make('birthdate')
                                            ->label('Date of Birth')
                                            ->columnSpan(1)
                                            ->disabled(fn (string $operation): bool => $operation === 'create'),
                                    ])
                                    ->visible(fn (string $operation): bool => $operation !== 'create'),

                                Forms\Components\Select::make('profile.gender')
                                    ->label('Gender')
                                    ->options([
                                        'male' => 'Male',
                                        'female' => 'Female',
                                        'other' => 'Other'
                                    ])
                                    ->relationship('profile','gender')
                                    ->columnSpan(1)
                                    ->visible(fn (string $operation): bool => $operation === 'edit'),

                                Forms\Components\Select::make('profile.city')
                                    ->label('City of Residence')
                                    ->options(Location::cities())
                                    ->relationship('profile', 'city')
                                    ->visible(fn (string $operation): bool => $operation === 'edit')

                            ])
                            ->columns(3)
                            ->columnSpan(2),

                        Forms\Components\Section::make('Contact Information')
                            ->schema([
                                Forms\Components\Fieldset::make('phone')
                                    ->label(null)
                                    ->extraAttributes(['class' => 'border-none p-0'])
                                    ->schema([
                                        Forms\Components\TextInput::make('user.phone')
                                            ->label('Phone Number')
                                            ->tel()
                                            ->disabled(fn (string $operation): bool => $operation === 'create'),

                                    ])->columns(1),
                                Forms\Components\Fieldset::make('whatsapp')
                                    ->label(null)
                                    ->extraAttributes(['class' => 'border-none p-0'])
                                    ->relationship('profile')
                                    ->schema([
                                        Forms\Components\TextInput::make('whatsapp')
                                            ->label('WhatsApp Number')
                                            ->tel()
                                            ->columnSpanFull()
                                            ->disabled(fn (string $operation): bool => $operation === 'create')
                                    ])
                                    ->columns(1)
                            ])
                            ->columnSpan(1)
                            ->columns(1)
                            ->visible(fn (string $operation): bool => $operation !== 'create'),

                    ])->visible(fn (string $operation): bool => $operation !== 'create'),

                Forms\Components\Fieldset::make('Gaming Experience')
                    ->label(null)
                    ->relationship('profile')
                    ->extraAttributes(['class' => 'border-none p-0'])
                    ->schema([
                        Forms\Components\Section::make('Gaming Experience')
                            ->schema([
                                Forms\Components\Select::make('skill_level')
                                    ->label('Skill Level in Gran Turismo 7')
                                    ->options([
                                        'beginner' => 'Beginner',
                                        'intermediate' => 'Intermediate',
                                        'expert' => 'Expert',
                                    ])
                                    ->disabled(fn (string $operation): bool => $operation === 'create'),
                                Forms\Components\Toggle::make('has_ps5')
                                    ->label('Do you own a PS5?')
                                    ->disabled(fn (string $operation): bool => $operation === 'create'),
                                Forms\Components\Select::make('primary_platform')
                                    ->label('Primary Gaming Platform')
                                    ->options([
                                        'ps5' => 'PlayStation 5',
                                        'ps4' => 'PlayStation 4',
                                        'pc' => 'PC',
                                        'other' => 'Other Platform',
                                    ])
                                    ->disabled(fn (string $operation): bool => $operation === 'create'),
                                Forms\Components\Select::make('weekly_hours')
                                    ->label('Weekly Play Hours')
                                    ->options([
                                        'less_than_5' => 'Less than 5 hours',
                                        '5_to_10' => '5 to 10 hours',
                                        'more_than_10' => 'More than 10 hours',
                                    ])
                                    ->disabled(fn (string $operation): bool => $operation === 'create'),
                            ]),
                    ])
                    ->visible(fn (string $operation): bool => $operation !== 'create'),

                Forms\Components\Fieldset::make('gaming Preferences')
                    ->label(null)
                    ->relationship('profile')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Section::make('Game Preferences')
                                    ->schema([
                                        Forms\Components\CheckboxList::make('favorite_games')
                                            ->label('Favorite Games')
                                            ->options([
                                                'fifa' => 'FIFA (FC25)',
                                                'pes' => 'PES',
                                                'gt7' => 'Gran Turismo 7',
                                                'cod' => 'Call of Duty',
                                                'fortnite' => 'Fortnite',
                                                'apex' => 'Apex Legends',
                                                'minecraft' => 'Minecraft',
                                                'gta' => 'GTA V'
                                            ])
                                            ->afterStateHydrated(function ($component, $state) {
                                                if (is_string($state)) {
                                                    $component->state(json_decode($state, true));
                                                }
                                            })
                                            ->columns(2)
                                            ->disabled(fn (string $operation): bool => $operation === 'create'),

                                        Forms\Components\Select::make('gt7_ranking')
                                            ->label('Where does Gran Turismo 7 rank among your favorites?')
                                            ->options([
                                                'top1' => 'My favorite game',
                                                'top3' => 'In my top 3 games',
                                                'top5' => 'In my top 5 games',
                                                'lower' => 'Lower than that'
                                            ])
                                            ->disabled(fn (string $operation): bool => $operation === 'create'),
                                    ]),

                                Forms\Components\Section::make('Toyota GR Knowledge')
                                    ->schema([
                                        Forms\Components\Select::make('toyota_gr_knowledge')
                                            ->label('How familiar are you with Toyota GR cars?')
                                            ->options([
                                                'expert' => 'I know them well and follow their news',
                                                'knowledgeable' => 'I have some knowledge about them',
                                                'heard' => 'I\'ve only heard of them',
                                                'unknown' => 'I don\'t know them at all'
                                            ])
                                            ->disabled(fn (string $operation): bool => $operation === 'create'),
                                        Forms\Components\Textarea::make('favorite_car')
                                            ->label('What is your favorite car (any brand) and why?')
                                            ->columnSpanFull()
                                            ->disabled(fn (string $operation): bool => $operation === 'create'),
                                    ]),
                            ]),
                    ])
                    ->columns(1)
                    ->visible(fn (string $operation): bool => $operation !== 'create'),

                Forms\Components\Fieldset::make('Tournament Experience')
                    ->label(null)
                    ->relationship('profile')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Section::make('Tournament Experience')
                                    ->schema([
                                        Forms\Components\Toggle::make('participated_before')
                                            ->label('Did you participate in AMTC 2024?')
                                            ->disabled(fn (string $operation): bool => $operation === 'create'),
                                        Forms\Components\Toggle::make('wants_training')
                                            ->label('Would you like to participate in training before the qualifiers?')
                                            ->disabled(fn (string $operation): bool => $operation === 'create'),
                                        Forms\Components\Toggle::make('join_whatsapp')
                                            ->label('Would you like to join the participants WhatsApp channel?')
                                            ->disabled(fn (string $operation): bool => $operation === 'create'),
                                    ]),

                                Forms\Components\Section::make('Additional Information')
                                    ->schema([
                                        Forms\Components\Select::make('heard_about')
                                            ->label('How did you hear about the tournament?')
                                            ->options([
                                                'social_media' => 'Social Media',
                                                'friends' => 'Friends & Family',
                                                'gaming_cafes' => 'Gaming Cafes',
                                                'websites' => 'Websites'
                                            ])
                                            ->disabled(fn (string $operation): bool => $operation === 'create'),
                                        Forms\Components\CheckboxList::make('motivation')
                                            ->label('What motivates you most to participate?')
                                            ->options([
                                                'prizes' => 'Prizes',
                                                'love_cars' => 'Love of cars/driving',
                                                'challenge' => 'Challenge & Competition',
                                                'toyota_experience' => 'Toyota GR experience',
                                                'skill_development' => 'Skill development'
                                            ])
                                            ->afterStateHydrated(function ($component, $state) {
                                                if (is_string($state)) {
                                                    $component->state(json_decode($state, true));
                                                }
                                            })
                                            ->columns(2)
                                            ->disabled(fn (string $operation): bool => $operation === 'create'),

                                        Forms\Components\Select::make('preferred_time')
                                            ->label('Preferred participation time')
                                            ->options([
                                                'afternoon' => 'Afternoon',
                                                'evening' => 'Evening',
                                                'weekend' => 'Weekends only',
                                                'flexible' => 'Flexible (any time)'
                                            ])
                                            ->disabled(fn (string $operation): bool => $operation === 'create'),
                                        Forms\Components\Textarea::make('suggestions')
                                            ->label('Any suggestions to improve the tournament experience?')
                                            ->columnSpanFull()
                                            ->disabled(fn (string $operation): bool => $operation === 'create'),
                                    ]),
                            ]),
                    ])
                    ->columns(1)
                    ->visible(fn (string $operation): bool => $operation !== 'create'),

                Forms\Components\Section::make('Admin Settings')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->confirmed()
                            ->minLength(8)
                            ->hidden(fn (string $operation): bool => $operation === 'edit' || $operation === 'view'),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->password()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(false)
                            ->hidden(fn (string $operation): bool => $operation === 'edit' || $operation === 'view'),
                        Forms\Components\Select::make('type')
                            ->options([
                                '1' => 'Admin',
                                '0' => 'User',
                            ])
                            ->default('User')
                            ->disabled(fn (string $operation): bool => $operation === 'edit' || $operation === 'view'),
                    ])
                    ->visible(fn (string $operation): bool => $operation !== 'create' && auth()->user()?->type === 1)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name'),
                TextColumn::make('tournament.title'),
                TextColumn::make('location'),
                Tables\Columns\TextColumn::make('profile.skill_level')
                    ->label('Skill Level')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'expert' => 'Expert',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'beginner' => 'gray',
                        'intermediate' => 'info',
                        'expert' => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tournament_id')
                    ->label('Tournament')
                    ->options(
                        Tournament::all()->mapWithKeys(function ($tournament) {
                            $formattedDate = \Carbon\Carbon::parse($tournament->date)->format('Y-m-d');
                            return [
                                $tournament->id => "{$tournament->title} . {$formattedDate}"
                            ];
                        })->toArray()
                    )
                    ->searchable(),

                Tables\Filters\SelectFilter::make('location')
                    ->label('Location')
                    ->options(
                        Leaderboard::query()
                            ->distinct()
                            ->pluck('location', 'location')
                            ->toArray()
                    )
                    ->searchable(),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListParticipants::route('/'),
            'create' => Pages\CreateParticipant::route('/create'),
            'view' => Pages\ViewParticipant::route('/{record}'),
            'edit' => Pages\EditParticipant::route('/{record}/edit'),
        ];
    }
}
