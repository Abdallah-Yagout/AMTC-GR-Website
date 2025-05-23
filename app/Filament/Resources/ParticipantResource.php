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

    public static function getRecordRouteKeyName(): string
    {
        return 'user_id';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Section::make('Basic Information')
                            ->relationship('user')
                            ->schema([
                                Forms\Components\FileUpload::make('profile_photo_path')
                                    ->image()
                                    ->avatar()
                                    ->directory('gt-cup-profiles')
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('name')
                                    ->label('Full Name')
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->columnSpan(1),

                                Forms\Components\Fieldset::make('birthdate')
                                    ->label(null) // Hides the title
                                    ->relationship('profile')
                                    ->extraAttributes(['class' => 'border-none p-0']) // Removes border and padding
                                    ->schema([
                                        Forms\Components\DatePicker::make('birthdate')
                                            ->label('Date of Birth')
                                            ->columnSpan(1),
                                    ]),
                                Forms\Components\Select::make('profile.gender')
                                    ->label('Gender')
                                    ->options([
                                        'male' => 'Male',
                                        'female' => 'Female',
                                        'other' => 'Other'
                                    ])
                                    ->relationship('profile','gender')
                                    ->columnSpan(1),

                                Forms\Components\Select::make('profile.city')
                                    ->label('City of Residence')
                                    ->options(Location::cities())
                                    ->relationship('profile', 'city')

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
                                            ->tel(),

                                    ])->columns(1),
                                Forms\Components\Fieldset::make('whatsapp')
                                    ->label(null)
                                    ->extraAttributes(['class' => 'border-none p-0'])
                                    ->relationship('profile')
                                    ->schema([
                                        Forms\Components\TextInput::make('whatsapp')
                                            ->label('WhatsApp Number')
                                            ->tel()
                                            ->columnSpanFull()  // This makes it take full width
                                    ])
                                    ->columns(1)  // Ensures the fieldset has only one column
                            ])
                            ->columnSpan(1)
                            ->columns(1),

                    ]),

//                // SECTION GROUP 2: Personal Details + Gaming Experience
                Forms\Components\Fieldset::make('Gaming Experience')
                    ->label(null) // Hides the title
                    ->relationship('profile')
                    ->extraAttributes(['class' => 'border-none p-0']) // Removes border and padding
                    ->schema([
                        Forms\Components\Section::make('Gaming Experience')
                            ->schema([
                                Forms\Components\Select::make('skill_level')
                                    ->label('Skill Level in Gran Turismo 7')
                                    ->options([
                                        'beginner' => 'Beginner',
                                        'intermediate' => 'Intermediate',
                                        'expert' => 'Expert',
                                    ]),
                                Forms\Components\Toggle::make('has_ps5')
                                    ->label('Do you own a PS5?'),
                                Forms\Components\Select::make('primary_platform')
                                    ->label('Primary Gaming Platform')
                                    ->options([
                                        'ps5' => 'PlayStation 5',
                                        'ps4' => 'PlayStation 4',
                                        'pc' => 'PC',
                                        'other' => 'Other Platform',
                                    ]),
                                Forms\Components\Select::make('weekly_hours')
                                    ->label('Weekly Play Hours')
                                    ->options([
                                        'less_than_5' => 'Less than 5 hours',
                                        '5_to_10' => '5 to 10 hours',
                                        'more_than_10' => 'More than 10 hours',
                                    ]),
                            ]),
                    ]),
////
                Forms\Components\Fieldset::make('gaming Preferences')
                    ->label(null)
                    ->relationship('profile')
                    ->schema([
                        // SECTION GROUP 3: Game Preferences + Toyota GR Knowledge
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
                                                // Optional: Convert string to array if needed
                                                if (is_string($state)) {
                                                    $component->state(json_decode($state, true));
                                                }
                                            })
                                            ->columns(2),

                                        Forms\Components\Select::make('gt7_ranking')
                                            ->label('Where does Gran Turismo 7 rank among your favorites?')
                                            ->options([
                                                'top1' => 'My favorite game',
                                                'top3' => 'In my top 3 games',
                                                'top5' => 'In my top 5 games',
                                                'lower' => 'Lower than that'
                                            ])
                                        ,
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
                                        ,
                                        Forms\Components\Textarea::make('favorite_car')
                                            ->label('What is your favorite car (any brand) and why?')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columns(1),

                Forms\Components\Fieldset::make('Tournament Experience')
                    ->label(null)
                    ->relationship('profile')
                    ->schema([
                        // SECTION GROUP 4: Tournament Experience + Additional Info
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Section::make('Tournament Experience')
                                    ->schema([
                                        Forms\Components\Toggle::make('participated_before')
                                            ->label('Did you participate in AMTC 2024?')
                                        ,
                                        Forms\Components\Toggle::make('wants_training')
                                            ->label('Would you like to participate in training before the qualifiers?')
                                        ,
                                        Forms\Components\Toggle::make('join_whatsapp')
                                            ->label('Would you like to join the participants WhatsApp channel?')
                                        ,
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
                                        ,
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
                                                // Optional: Convert string to array if needed
                                                if (is_string($state)) {
                                                    $component->state(json_decode($state, true));
                                                }
                                            })->columns(2),

                                        Forms\Components\Select::make('preferred_time')
                                            ->label('Preferred participation time')
                                            ->options([
                                                'afternoon' => 'Afternoon',
                                                'evening' => 'Evening',
                                                'weekend' => 'Weekends only',
                                                'flexible' => 'Flexible (any time)'
                                            ])
                                        ,
                                        Forms\Components\Textarea::make('suggestions')
                                            ->label('Any suggestions to improve the tournament experience?')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->columns(1),

//
//                // Admin Settings (Full Width)
//                Forms\Components\Section::make('Admin Settings')
//                    ->schema([
//                        Forms\Components\TextInput::make('password')
//                            ->password()
//                            ->required(fn (string $operation): bool => $operation === 'create')
//                            ->dehydrated(fn (?string $state): bool => filled($state))
//                            ->confirmed()
//                            ->minLength(8),
//                        Forms\Components\TextInput::make('password_confirmation')
//                            ->password()
//                            ->required(fn (string $operation): bool => $operation === 'create')
//                            ->dehydrated(false),
//                        Forms\Components\Select::make('type')
//                            ->options([
//                                'admin' => 'Admin',
//                                'participant' => 'Participant',
//                                'judge' => 'Judge'
//                            ])
//                            ->default('participant')
//                        ,
//                    ])
//                    ->visible(fn (): bool => auth()->user()?->type === 'admin'),
//                Select::make('tournament_id')
//                    ->label('Tournament')
//                    ->options(
//                        Tournament::all()->mapWithKeys(function ($tournament) {
//                            $formattedDate = Carbon::parse($tournament->date)->format('Y-m-d');
//                            return [
//                                $tournament->id => "{$tournament->title} . {$formattedDate}"
//                            ];
//                        })->toArray()
//                    )
//                    ->searchable()
//                    ->reactive()
//                    ->afterStateUpdated(function (Set $set, ?string $state) {
//                        $tournament = Tournament::find($state);
//                        if ($tournament) {
//                            // Set available locations
//                            $locations = collect($tournament->location)
//                                ->mapWithKeys(fn($loc) => [$loc => $loc])
//                                ->toArray();
//                            $set('available_locations', $locations);
//                        }
//                    }),
//                Forms\Components\Select::make('user_id')
//                    ->options(User::pluck('name','id')),
//                Select::make('location')
//                    ->label('Location')
//                    ->options(function (Get $get) {
//                        $tournament = Tournament::find($get('tournament_id'));
//                        return $tournament?->location
//                            ? collect($tournament->location)->mapWithKeys(fn($loc) => [$loc => $loc])->toArray()
//                            : [];
//                    })
//                    ->searchable()
//                    ->required(),

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
