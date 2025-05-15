<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParticipantResource\Pages;
use App\Filament\Resources\ParticipantResource\RelationManagers;
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
                        }
                    }),
                Forms\Components\Select::make('user_id')
                    ->options(User::pluck('name','id')),
                Select::make('location')
                    ->label('Location')
                    ->options(function (Get $get) {
                        $tournament = Tournament::find($get('tournament_id'));
                        return $tournament?->location
                            ? collect($tournament->location)->mapWithKeys(fn($loc) => [$loc => $loc])->toArray()
                            : [];
                    })
                    ->searchable()
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tournament.title'),
                TextColumn::make('user.name'),
                TextColumn::make('location'),
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
            'edit' => Pages\EditParticipant::route('/{record}/edit'),
        ];
    }
}
