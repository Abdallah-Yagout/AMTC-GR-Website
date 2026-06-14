<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoResource\Pages;
use App\Models\Video;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static ?string $navigationIcon = 'heroicon-o-film';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),

                Select::make('source_type')
                    ->label('Source Type')
                    ->options([
                        'external' => 'External URL',
                        'upload' => 'Uploaded MP4',
                    ])
                    ->default('external')
                    ->required()
                    ->live(),

                TextInput::make('video_url')
                    ->label('YouTube / Vimeo URL')
                    ->url()
                    ->maxLength(2000)
                    ->required(fn (Get $get): bool => $get('source_type') === 'external')
                    ->visible(fn (Get $get): bool => $get('source_type') === 'external'),

                FileUpload::make('video_file')
                    ->label('MP4 File')
                    ->directory('videos')
                    ->acceptedFileTypes(['video/mp4'])
                    ->maxSize(102400)
                    ->helperText('MP4 only. Max 100MB (requires PHP upload limits >= 100MB).')
                    ->required(fn (Get $get): bool => $get('source_type') === 'upload')
                    ->visible(fn (Get $get): bool => $get('source_type') === 'upload'),

                FileUpload::make('thumbnail')
                    ->label('Thumbnail (optional)')
                    ->directory('videos/thumbnails')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),

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
                ImageColumn::make('thumbnail')
                    ->label('Thumb')
                    ->size(56)
                    ->defaultImageUrl(url('/img/logo.png')),
                TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('source_type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'upload' ? 'Upload' : 'External'),
                IconColumn::make('video_url')
                    ->label('Linked')
                    ->boolean()
                    ->state(fn (Video $record): bool => ! empty($record->video_url)),
                ToggleColumn::make('is_active'),
                TextColumn::make('sort_order')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
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
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}
