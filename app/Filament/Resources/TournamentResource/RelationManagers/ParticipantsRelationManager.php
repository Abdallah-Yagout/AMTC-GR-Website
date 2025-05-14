<?php

namespace App\Filament\Resources\TournamentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParticipantsRelationManager extends RelationManager
{
    protected static string $relationship = 'participant';
    protected static ?string $title = 'Participants';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Participant')
                    ->options(function () {
                        $tournament = $this->getOwnerRecord();
                        return \App\Models\User::whereHas('participant', function ($query) use ($tournament) {
                            $query->where('tournament_id', $tournament->tournament_id);
                        })->pluck('name', 'id');
                    })->preload()
                    ->searchable()
                    ->required(),


                Forms\Components\Select::make('location')
                    ->options(function () {
                        $tournament = $this->getOwnerRecord();
                        $locations = is_array($tournament->location) ? $tournament->location : [];
                        return array_combine(
                            $locations,
                            array_map('ucfirst', $locations)
                        );
                    })

                    ->required(),

                Forms\Components\TextInput::make('time_taken')
                    ->numeric()
                    ->suffix('seconds'),

                Forms\Components\TextInput::make('position')
                    ->numeric(),

                Forms\Components\Toggle::make('is_winner'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tournament_id')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Participant')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('location')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sanaa' => 'info',
                        'aden' => 'success',
                        'mukalla' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('time_taken')
                    ->label('Time (s)')
                    ->sortable(),

                Tables\Columns\TextColumn::make('position')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_winner')
                    ->boolean()
                    ->label('Winner'),
                ])
            ->filters([

                Tables\Filters\SelectFilter::make('location')
                    ->options(function () {
                        $tournament = $this->getOwnerRecord();
                        $locations = is_array($tournament->location) ? $tournament->location : [];

                        return array_combine(
                            $locations,
                            array_map('ucfirst', $locations)
                        );
                    }),


                Tables\Filters\Filter::make('winners_only')
                    ->label('Winners only')
                    ->query(fn (Builder $query): Builder => $query->where('is_winner', true)),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
