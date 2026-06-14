<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameDefinitionResource\Pages;
use App\Models\GameDefinition;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GameDefinitionResource extends Resource
{
    protected static ?string $model = GameDefinition::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationGroup = 'Arcade';

    protected static ?string $navigationLabel = 'Games';

    protected static ?string $modelLabel = 'game';

    protected static ?string $pluralModelLabel = 'games';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('slug')
                    ->label(__('Slug'))
                    ->required()
                    ->maxLength(64)
                    ->live(onBlur: true)
                    ->disabled(fn (string $operation): bool => $operation === 'edit')
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label(__('Description'))
                    ->rows(2)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_enabled')
                    ->label(__('Enabled'))
                    ->default(true),
                Forms\Components\TextInput::make('sort_order')
                    ->label(__('Sort order'))
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('reward_type')
                    ->label(__('Reward type'))
                    ->options([
                        GameDefinition::REWARD_POINTS => __('Points'),
                        GameDefinition::REWARD_HIGH_SCORE => __('High score'),
                    ])
                    ->required(),
                Forms\Components\TextInput::make('points_per_completion')
                    ->label(__('Points per completion'))
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(999999),
                Forms\Components\TextInput::make('points_cooldown_hours')
                    ->label(__('Points cooldown (hours)'))
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(8760)
                    ->helperText(__('Minimum hours between point awards per player for this game.')),
                Forms\Components\Fieldset::make(__('Matching game options'))
                    ->schema([
                        Forms\Components\TextInput::make('config.pair_count')
                            ->label(__('Pair count (4–10)'))
                            ->numeric()
                            ->minValue(4)
                            ->maxValue(GameDefinition::MATCHING_MAX_PAIR_COUNT)
                            ->default(8)
                            ->helperText(__('Ten GR car images are available; pair count cannot exceed 10.')),
                    ])
                    ->visible(fn (Get $get, ?GameDefinition $record): bool => ($get('slug') ?: $record?->slug) === 'matching'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slug')
                    ->label(__('Slug'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_enabled')
                    ->label(__('On'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('reward_type')
                    ->label(__('Reward')),
                Tables\Columns\TextColumn::make('points_per_completion')
                    ->label(__('Pts / win'))
                    ->numeric(),
                Tables\Columns\TextColumn::make('points_cooldown_hours')
                    ->label(__('Cooldown h'))
                    ->numeric(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListGameDefinitions::route('/'),
            'create' => Pages\CreateGameDefinition::route('/create'),
            'edit' => Pages\EditGameDefinition::route('/{record}/edit'),
        ];
    }
}
