<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameUserStatResource\Pages;
use App\Models\GameUserStat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GameUserStatResource extends Resource
{
    protected static ?string $model = GameUserStat::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationGroup = 'Point System';

    protected static ?string $navigationLabel = 'Player scores';

    protected static ?string $modelLabel = 'player score';

    protected static ?string $pluralModelLabel = 'player scores';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label(__('User'))
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
                Forms\Components\TextInput::make('points')
                    ->label(__('Games points'))
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(99999999)
                    ->required()
                    ->default(0),
                Forms\Components\Group::make([
                    Forms\Components\Placeholder::make('profile_completed_awarded_at')
                        ->label(__('Profile bonus awarded'))
                        ->content(fn (?GameUserStat $record): string => $record?->profile_completed_awarded_at?->toDateTimeString() ?? '—'),
                    Forms\Components\Placeholder::make('image_uploaded_awarded_at')
                        ->label(__('Image bonus awarded'))
                        ->content(fn (?GameUserStat $record): string => $record?->image_uploaded_awarded_at?->toDateTimeString() ?? '—'),
                    Forms\Components\Placeholder::make('last_daily_claim_date')
                        ->label(__('Last daily claim'))
                        ->content(fn (?GameUserStat $record): string => $record?->last_daily_claim_date?->toDateString() ?? '—'),
                ])
                    ->columns(1)
                    ->visible(fn (string $operation): bool => $operation === 'edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Player'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('points')
                    ->label(__('Points'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('points', 'desc')
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
            'index' => Pages\ListGameUserStats::route('/'),
            'create' => Pages\CreateGameUserStat::route('/create'),
            'edit' => Pages\EditGameUserStat::route('/{record}/edit'),
        ];
    }
}
