<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TournamentResource\Pages;
use App\Filament\Resources\TournamentResource\RelationManagers;
use App\Helpers\Location;
use App\Models\Tournament;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TournamentResource extends Resource
{
    protected static ?string $model = Tournament::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('language tab')
            ->tabs([
                Tab::make('English')
                    ->schema([
                        TextInput::make('title'),
                        TextInput::make('description'),
                        Select::make('location')
                            ->options(Location::cities())->multiple(),
                        Select::make('tournament_id')
                            ->options(
                                Tournament::pluck('title','id')
                            ),
                        Forms\Components\DatePicker::make('start_date'),
                        Forms\Components\DatePicker::make('end_date'),
                        FileUpload::make('image')
                            ->directory('tournaments')
                    ]),
                Tab::make('Arabic')
                    ->schema([
                        TextInput::make('title_ar')
                            ->required(),
                        TextInput::make('description_ar')
                            ->required(),
                    ]),
            ])->persistTabInQueryString()
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('location')
                    ->label('Location')
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state)
                    ->badge()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image')
                ->size(100)
                ->circular(),
                Tables\Columns\TextColumn::make('start_date'),
               Tables\Columns\ToggleColumn::make('status')

            ])
            ->filters([
                SelectFilter::make('location')
                    ->label('City')
                    ->options(function () {
                        $query = self::getEloquentQuery();
                        return $query
                            ->get()
                            ->flatMap(function ($record) {
                                return $record->location ?? []; // Each location is an array
                            })
                            ->unique()
                            ->filter()
                            ->sort()
                            ->mapWithKeys(fn ($loc) => [$loc => $loc])
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->query(function (Builder $query, array $data): Builder {
                        if (!empty($data['value'])) {
                            return $query->whereJsonContains('location', $data['value']);
                        }
                        return $query;
                    })


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
//            RelationManagers\ParticipantsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTournaments::route('/'),
            'create' => Pages\CreateTournament::route('/create'),
            'edit' => Pages\EditTournament::route('/{record}/edit'),
        ];
    }
}
