<?php
namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Helpers\Location;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ])
            ->withTrashed()->with('profile');
    }

    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

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
                                    ->columnSpanFull()
                                    ->visible(fn (string $operation): bool => $operation !== 'create')
                                    ->disabled(fn (string $operation): bool => $operation === 'view'),
                                Forms\Components\TextInput::make('name')
                                    ->label('Full Name')
                                    ->required()
                                    ->columnSpan(1)
                                    ->disabled(fn (string $operation): bool => $operation === 'view'),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->columnSpan(1)
                                    ->disabled(fn (string $operation): bool => $operation === 'view'),

                                Forms\Components\Fieldset::make('birthdate')
                                    ->label(null)
                                    ->relationship('profile')
                                    ->extraAttributes(['class' => 'border-none p-0'])
                                    ->schema([
                                        Forms\Components\DatePicker::make('birthdate')
                                            ->label('Date of Birth')
                                            ->columnSpan(1)
                                            ->disabled(fn (string $operation): bool => $operation === 'create' || $operation === 'view'),
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
                                        Forms\Components\TextInput::make('phone')
                                            ->label('Phone Number')
                                            ->tel()
                                            ->disabled(fn (string $operation): bool => $operation === 'create' || $operation === 'view'),
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
                                            ->disabled(fn (string $operation): bool => $operation === 'create' || $operation === 'view')
                                    ])
                                    ->columns(1)
                            ])
                            ->columnSpan(1)
                            ->columns(1)
                            ->visible(fn (string $operation): bool => $operation !== 'create'),

                    ]),

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
                                    ->disabled(fn (string $operation): bool => $operation === 'create' || $operation === 'view'),
                                Forms\Components\Toggle::make('has_ps5')
                                    ->label('Do you own a PS5?')
                                    ->disabled(fn (string $operation): bool => $operation === 'create' || $operation === 'view'),
                                Forms\Components\Select::make('primary_platform')
                                    ->label('Primary Gaming Platform')
                                    ->options([
                                        'ps5' => 'PlayStation 5',
                                        'ps4' => 'PlayStation 4',
                                        'pc' => 'PC',
                                        'other' => 'Other Platform',
                                    ])
                                    ->disabled(fn (string $operation): bool => $operation === 'create' || $operation === 'view'),
                                Forms\Components\Select::make('weekly_hours')
                                    ->label('Weekly Play Hours')
                                    ->options([
                                        'less_than_5' => 'Less than 5 hours',
                                        '5_to_10' => '5 to 10 hours',
                                        'more_than_10' => 'More than 10 hours',
                                    ])
                                    ->disabled(fn (string $operation): bool => $operation === 'create' || $operation === 'view'),
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
                                            ->disabled(fn (string $operation): bool => $operation === 'create' || $operation === 'view'),

                                        Forms\Components\Select::make('gt7_ranking')
                                            ->label('Where does Gran Turismo 7 rank among your favorites?')
                                            ->options([
                                                'top1' => 'My favorite game',
                                                'top3' => 'In my top 3 games',
                                                'top5' => 'In my top 5 games',
                                                'lower' => 'Lower than that'
                                            ])
                                            ->disabled(fn (string $operation): bool => $operation === 'create' || $operation === 'view'),
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
                                            ->disabled(fn (string $operation): bool => $operation === 'create' || $operation === 'view'),
                                        Forms\Components\Textarea::make('favorite_car')
                                            ->label('What is your favorite car (any brand) and why?')
                                            ->columnSpanFull()
                                            ->disabled(fn (string $operation): bool => $operation === 'create' || $operation === 'view'),
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
                                            ->disabled(fn (string $operation): bool => $operation === 'create' || $operation === 'view'),
                                        Forms\Components\Toggle::make('wants_training')
                                            ->label('Would you like to participate in training before the qualifiers?')
                                            ->disabled(fn (string $operation): bool => $operation === 'create' || $operation === 'view'),
                                        Forms\Components\Toggle::make('join_whatsapp')
                                            ->label('Would you like to join the participants WhatsApp channel?')
                                            ->disabled(fn (string $operation): bool => $operation === 'create' || $operation === 'view'),
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
                                            ->disabled(fn (string $operation): bool => $operation === 'create' || $operation === 'view'),
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
                                            ->disabled(fn (string $operation): bool => $operation === 'create' || $operation === 'view'),

                                        Forms\Components\Select::make('preferred_time')
                                            ->label('Preferred participation time')
                                            ->options([
                                                'afternoon' => 'Afternoon',
                                                'evening' => 'Evening',
                                                'weekend' => 'Weekends only',
                                                'flexible' => 'Flexible (any time)'
                                            ])
                                            ->disabled(fn (string $operation): bool => $operation === 'create' || $operation === 'view'),
                                        Forms\Components\Textarea::make('suggestions')
                                            ->label('Any suggestions to improve the tournament experience?')
                                            ->columnSpanFull()
                                            ->disabled(fn (string $operation): bool => $operation === 'create' || $operation === 'view'),
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
                            ->default('0')
                            ->disabled(fn (string $operation): bool => $operation === 'edit' || $operation === 'view'),
                    ])
                    ->visible(fn (): bool => auth()->user()?->type === 1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo_path')
                    ->label('Avatar')
                    ->defaultImageUrl(fn ($record) => avatar_url($record->name))
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('profile.city')
                    ->label('City'),
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
                        '1' => 'Admin',
                        '0' => 'User',
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
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
