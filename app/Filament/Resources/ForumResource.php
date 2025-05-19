<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ForumResource\Pages;
use App\Filament\Resources\ForumResource\RelationManagers;
use App\Models\Forum;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ForumResource extends Resource
{
    protected static ?string $model = Forum::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Language Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('English')
                            ->schema([
                                TextInput::make('title')
                                    ->required(),
                                FileUpload::make('image')
                                    ->directory('forum'),
                                RichEditor::make('body'),
                            ]),
                        Forms\Components\Tabs\Tab::make('العربية')
                            ->schema([
                                TextInput::make('title_ar')
                                    ->label("العنوان")
                                    ->required()
                                    ->extraInputAttributes(['dir' => 'rtl']),
                                RichEditor::make('body_ar')
                                    ->label("المحتوى")
                                    ->extraInputAttributes(['dir' => 'rtl']),
                            ]),
                    ])
                    ->persistTabInQueryString()
                ->columnSpanFull(), // Optional: remembers the active tab
            ]);
    }
    public static function saveTranslations(Model $record, array $data): void
    {
        $translatableFields = ['title', 'body'];

        foreach ($translatableFields as $field) {
            foreach (['en', 'ar'] as $locale) {
                $fieldName = $locale === 'en' ? $field : "{$field}_ar";

                if (isset($data[$fieldName])) {
                    $record->translations()->updateOrCreate(
                        ['field' => $field, 'locale' => $locale],
                        ['value' => $data[$fieldName]]
                    );
                }
            }
        }
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                Tables\Columns\ImageColumn::make('image')
                    ->size(60)
                ->circular(),
                TextColumn::make('upvotes')
                ->badge(),
                Tables\Columns\ToggleColumn::make('status')

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
            'index' => Pages\ListForums::route('/'),
            'create' => Pages\CreateForum::route('/create'),
            'edit' => Pages\EditForum::route('/{record}/edit'),
        ];
    }
}
