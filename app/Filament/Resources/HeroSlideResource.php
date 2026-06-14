<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroSlideResource\Pages;
use App\Models\HeroSlide;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class HeroSlideResource extends Resource
{
    protected static ?string $model = HeroSlide::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Hero Slides';

    protected static ?string $navigationGroup = 'Website';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Language Tabs')
                    ->tabs([
                        Tab::make('English')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Title (EN)')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('subtitle')
                                    ->label('Subtitle (EN)')
                                    ->rows(3),
                                TextInput::make('cta_text')
                                    ->label('CTA Text (EN)')
                                    ->maxLength(100),
                            ]),
                        Tab::make('Arabic')
                            ->schema([
                                TextInput::make('title_ar')
                                    ->label('Title (AR)')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('subtitle_ar')
                                    ->label('Subtitle (AR)')
                                    ->rows(3),
                                TextInput::make('cta_text_ar')
                                    ->label('CTA Text (AR)')
                                    ->maxLength(100),
                            ]),
                    ])
                    ->persistTabInQueryString()
                    ->columnSpanFull(),

                TextInput::make('cta_url')
                    ->label('CTA URL')
                    ->url()
                    ->maxLength(2048),

                FileUpload::make('image')
                    ->required()
                    ->directory('hero-slides')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp']),

                Toggle::make('is_active')
                    ->default(true),

                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->size(80),
                TextColumn::make('title')
                    ->searchable()
                    ->limit(45),
                TextColumn::make('cta_url')
                    ->limit(40)
                    ->toggleable(),
                ToggleColumn::make('is_active'),
                TextColumn::make('sort_order')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->since()
                    ->sortable(),
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
            'index' => Pages\ListHeroSlides::route('/'),
            'create' => Pages\CreateHeroSlide::route('/create'),
            'edit' => Pages\EditHeroSlide::route('/{record}/edit'),
        ];
    }
}
