<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParticipantsResource\Pages;
use App\Filament\Resources\ParticipantsResource\RelationManagers;
use App\Models\participant;
use App\Models\Tournament;
use App\Models\User;
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

class ParticipantsResource extends Resource
{
    protected static ?string $model = participant::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                ->options(User::all()->pluck('name', 'id'))
                ->searchable(),
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
                            $locations = collect($tournament->locations) // assume locations is a JSON column like ["Aden", "Sana'a"]
                            ->mapWithKeys(fn($loc) => [$loc => $loc])
                                ->toArray();
                            $set('available_locations', $locations); // use a custom field or state holder if needed
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
                    ->required(),
                Forms\Components\TextInput::make('time_taken')
                ->numeric(),
                Forms\Components\TextInput::make('position')
                ->numeric(),
                Forms\Components\TextInput::make('is_winner')
                ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('tournament.title'),
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\TextColumn::make('is_winner')
                ->formatStateUsing(function ($state) {
                    return $state ? 'Yes' : 'No';
                })->badge(),
                Tables\Columns\TextColumn::make('position'),
            ])
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
            'index' => Pages\ListParticipants::route('/'),
            'create' => Pages\CreateParticipants::route('/create'),
            'edit' => Pages\EditParticipants::route('/{record}/edit'),
        ];
    }
}
