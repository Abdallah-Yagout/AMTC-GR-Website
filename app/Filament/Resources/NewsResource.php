<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Models\News;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Language Tabs')
                    ->tabs([
                        Tab::make('English')
                            ->schema([
                                TextInput::make('title')
                                    ->required(),
                                FileUpload::make('image')
                                    ->required()
                                    ->directory('events')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp']),
                                RichEditor::make('description')
                                    ->required(),
                                Toggle::make('status'),
                            ]),
                        Tab::make('Arabic')
                            ->schema([
                                TextInput::make('title_ar')
                                    ->required(),
                                RichEditor::make('description_ar')
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
                TextColumn::make('title'),
                Tables\Columns\ImageColumn::make('image')
                    ->circular()
                    ->size(70),
                ToggleColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->label('Duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->action(function (News $record): void {
                        $copy = $record->replicate();

                        $baseSlug = $record->slug ?: Str::slug((string) $record->title);
                        $baseSlug = $baseSlug !== '' ? $baseSlug : 'news';

                        $newSlug = $baseSlug.'-copy';
                        $counter = 2;

                        while (News::where('slug', $newSlug)->exists()) {
                            $newSlug = $baseSlug.'-copy-'.$counter;
                            $counter++;
                        }

                        $copy->slug = $newSlug;
                        $copy->save();
                    }),
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
