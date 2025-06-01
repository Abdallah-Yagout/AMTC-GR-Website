<?php

namespace App\Filament\Resources\TournamentResource\RelationManagers;

use App\Helpers\Location;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParticipantsRelationManager extends RelationManager
{
    protected static string $relationship = 'participants';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        // First Column: User Name Input
                        Fieldset::make('Label')
                            ->relationship('user')
                            ->extraAttributes(['class' => 'border-none p-0'])
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->columnSpanFull(), // Optional to control size inside fieldset
                            ])
                            ->columnSpan(1), // Force it to only take half

                        // Second Column: Location Select
                        Forms\Components\Select::make('location')
                            ->options(Location::cities())
                            ->label('Location')
                            ->columnSpan(1),
                    ]),
            ]);

    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Participant Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->badge()
                    ->searchable(),
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
