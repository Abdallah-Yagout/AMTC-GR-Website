<?php

namespace App\Filament\Resources\LeaderboardResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class ParticipantsRelationManager extends RelationManager
{
    protected static string $relationship = 'participants';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Remove leaderboard fields from the form since they're handled by the relation
                Forms\Components\Section::make('Participant Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Participant')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Hidden::make('tournament_id')
                            ->default(fn () => $this->getOwnerRecord()->tournament_id),

                        Forms\Components\Hidden::make('location')
                            ->default(fn () => $this->getOwnerRecord()->location),
                    ]),

                Forms\Components\Section::make('Performance')
                    ->schema([
                        Forms\Components\TextInput::make('time_taken')
                            ->numeric()
                            ->required(),

                        Forms\Components\TextInput::make('position')
                            ->numeric()
                            ->required(),

                        Forms\Components\Toggle::make('status')
                            ->required(),
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Participant'),

                Tables\Columns\TextColumn::make('tournament.title')
                    ->label('Tournament'),

                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('pivot.position'),
                Tables\Columns\TextColumn::make('pivot.time_taken'),


            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['leaderboard_id'] = $this->getOwnerRecord()->id;
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
