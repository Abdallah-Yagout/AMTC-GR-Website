<?php
namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
//    protected static ?string $navigationLabel = 'GT Cup Participants';
//    protected static ?string $modelLabel = 'Participant';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Section::make('Basic Information')
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

                                Forms\Components\DatePicker::make('birthdate')
                                    ->label('Date of Birth')
                                    ->columnSpan(1),

                                Forms\Components\Select::make('gender')
                                    ->options([
                                        'male' => 'Male',
                                        'female' => 'Female',
                                        'other' => 'Other'
                                    ])
                                    ->columnSpan(1),

                                Forms\Components\Select::make('city')
                                    ->label('City of Residence')
                                    ->options([
                                        'sanaa' => 'Sanaa',
                                        'aden' => 'Aden',
                                        'taiz' => 'Taiz',
                                        'hodeidah' => 'Hodeidah',
                                        'hadramout' => 'Hadramout - Mukalla',
                                        'dhamar' => 'Dhamar',
                                        'hajjah' => 'Hajjah',
                                        'ibb' => 'Ibb',
                                        'abyan' => 'Abyan',
                                        'shabwah' => 'Shabwah',
                                        'marib' => 'Marib',
                                        'other' => 'Other'
                                    ])
                                    ->columnSpan(2),
                            ])
                            ->columns(3)
                            ->columnSpan(2),

                        Forms\Components\Section::make('Contact Information')
                            ->schema([
                                Forms\Components\TextInput::make('phone')
                                    ->label('Phone Number')
                                    ->tel()
                                    ,
                                Forms\Components\TextInput::make('whatsapp')
                                    ->label('WhatsApp Number')
                                    ->tel(),
                            ])
                            ->columnSpan(1)
                            ->columns(1),

                    ]),

                // SECTION GROUP 2: Personal Details + Gaming Experience
                Forms\Components\Grid::make(3)
                    ->schema([


                        Forms\Components\Section::make('Gaming Experience')
                            ->schema([
                                Forms\Components\Select::make('skill_level')
                                    ->label('Skill Level in Gran Turismo 7')
                                    ->options([
                                        'beginner' => 'Beginner',
                                        'intermediate' => 'Intermediate',
                                        'expert' => 'Expert'
                                    ])
                                    ,
                                Forms\Components\Toggle::make('has_ps5')
                                    ->label('Do you own a PS5?')
                                    ,
                                Forms\Components\Select::make('primary_platform')
                                    ->label('Primary Gaming Platform')
                                    ->options([
                                        'ps5' => 'PlayStation 5',
                                        'ps4' => 'PlayStation 4',
                                        'pc' => 'PC',
                                        'other' => 'Other Platform'
                                    ])
                                    ,
                                Forms\Components\CheckboxList::make('regular_games')
                                    ->label('Games you play regularly')
                                    ->options([
                                        'gran_turismo' => 'Gran Turismo',
                                        'fifa' => 'EAFC / FIFA',
                                        'cod' => 'Call of Duty',
                                        'fortnite' => 'Fortnite',
                                        'minecraft' => 'Minecraft',
                                        'rocket_league' => 'Rocket League',
                                        'assetto_corsa' => 'Assetto Corsa',
                                        'rpg' => 'RPG Games',
                                        'other_racing' => 'Other Racing Games'
                                    ])
                                    ->columns(2),
                                Forms\Components\Select::make('weekly_hours')
                                    ->label('Weekly Play Hours')
                                    ->options([
                                        'less_than_5' => 'Less than 5 hours',
                                        '5_to_10' => '5 to 10 hours',
                                        'more_than_10' => 'More than 10 hours'
                                    ])
                                    ,
                            ]),
                    ]),

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
                                    ->columns(2),
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

                // Admin Settings (Full Width)
                Forms\Components\Section::make('Admin Settings')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->confirmed()
                            ->minLength(8),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->password()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(false),
                        Forms\Components\Select::make('type')
                            ->options([
                                'admin' => 'Admin',
                                'participant' => 'Participant',
                                'judge' => 'Judge'
                            ])
                            ->default('participant')
                            ,
                    ])
                    ->visible(fn (): bool => auth()->user()?->type === 'admin'),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo_path')
                    ->label('Avatar')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('City'),
                Tables\Columns\TextColumn::make('skill_level')
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
                Tables\Columns\IconColumn::make('has_ps5')
                    ->label('PS5')
                    ->boolean(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'participant' => 'success',
                        'judge' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('city')
                    ->options([
                        'sanaa' => 'Sanaa',
                        'aden' => 'Aden',
                        'taiz' => 'Taiz',
                        // ... other cities
                    ]),
                Tables\Filters\SelectFilter::make('skill_level')
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'expert' => 'Expert',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'admin' => 'Admin',
                        'participant' => 'Participant',
                        'judge' => 'Judge',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
//            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
