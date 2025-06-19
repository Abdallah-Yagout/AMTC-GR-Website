<?php

namespace App\Filament\Resources\LeaderboardResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ParticipantsRelationManager extends RelationManager
{
    protected static string $relationship = 'participant';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Tournament Assignment (create only)
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
                                $locations = $tournament?->location ?? [];

                                return collect($locations)
                                    ->filter(fn ($loc) => filled($loc))
                                    ->mapWithKeys(fn ($loc) => [(string) $loc => (string) $loc])
                                    ->toArray();
                            })
                            ->required()
                            ->reactive()
                    ])
                    ->columns(3)
                    ->visible(fn (string $operation): bool => $operation === 'create'),

                // Participant Details (edit/view)
                Forms\Components\Section::make('Participant Details')
                    ->schema([
                        Forms\Components\TextInput::make('user.name')
                            ->label('Participant Name')
                            ->disabled(),

                        Forms\Components\TextInput::make('location')
                            ->disabled(),
                    ])
                    ->visible(fn (string $operation): bool => $operation !== 'create'),

                // Leaderboard Fields - Access through owner record
                Forms\Components\Section::make('Leaderboard Information')
                    ->schema([
                        Forms\Components\TextInput::make('ownerRecord.time_taken')  // Access parent Leaderboard
                        ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('ownerRecord.position')    // Access parent Leaderboard
                        ->numeric()
                            ->required(),

                        Forms\Components\Toggle::make('ownerRecord.status')       // Access parent Leaderboard
                        ->required(),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Participant Name'),

                Tables\Columns\TextColumn::make('location'),

                Tables\Columns\TextColumn::make('ownerRecord.time_taken') // Access through owner record
                ->label('Time Taken'),

                Tables\Columns\TextColumn::make('ownerRecord.position')    // Access through owner record
                ->label('Position'),

                Tables\Columns\IconColumn::make('ownerRecord.status')     // Access through owner record
                ->boolean()
                    ->label('Status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
