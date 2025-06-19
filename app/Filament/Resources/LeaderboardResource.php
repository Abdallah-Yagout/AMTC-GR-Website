<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaderboardResource\Pages;
use App\Filament\Resources\LeaderboardResource\RelationManagers;
use App\Filament\Resources\LeaderboardResource\RelationManagers\ParticipantsRelationManager;
use App\Helpers\Location;
use App\Models\Leaderboard;
use App\Models\Participant;
use App\Models\Tournament;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class LeaderboardResource extends Resource
{
    protected static ?string $model = Leaderboard::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tournament_id')
                    ->label('Tournament')
                    ->options(
                        Tournament::all()->mapWithKeys(function ($tournament) {
                            $formattedDate = Carbon::parse($tournament->date)->format('Y-m-d');
                            return [
                                $tournament->id => "{$tournament->title} . {$formattedDate}"
                            ];
                        })->toArray()
                    )
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $tournament = Tournament::find($state);
                        if ($tournament) {
                            // Set available locations
                            $locations = collect($tournament->location)
                                ->mapWithKeys(fn($loc) => [$loc => $loc])
                                ->toArray();
                            $set('available_locations', $locations);

                            // Clear user selection when tournament changes
                            $set('user_id', null);
                        }
                    }),

                Select::make('location')
                    ->label('Location')
                    ->options(function (Get $get) {
                        $tournament = Tournament::find($get('tournament_id'));
                        return $tournament?->location
                            ? collect($tournament->location)->mapWithKeys(fn($loc) => [$loc => $loc])->toArray()
                            : [];
                    })
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn (Set $set) => $set('user_id', null)),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tournament.title'),
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\ToggleColumn::make('status')
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tournament_id')
                    ->label('Tournament')
                    ->options(function () {
                        return Tournament::query()
                            ->whereHas('leaderboards') // Only tournaments with leaderboards
                            ->select('id', 'title', 'start_date')
                            ->orderBy('start_date', 'desc')
                            ->get()
                            ->mapWithKeys(function ($tournament) {
                                return [
                                    $tournament->id => "{$tournament->title} • {$tournament->start_date}"
                                ];
                            });
                    })
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
            ParticipantsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaderboards::route('/'),
            'create' => Pages\CreateLeaderboard::route('/create'),
            'edit' => Pages\EditLeaderboard::route('/{record}/edit'),
        ];
    }
}
