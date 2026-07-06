<?php

namespace App\Filament\Resources\ParticipantResource\Pages;

use App\Filament\Exports\ParticipantExporter;
use App\Filament\Resources\ParticipantResource;
use App\Support\ParticipantExportSupport;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListParticipants extends ListRecords
{
    protected static string $resource = ParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->exporter(ParticipantExporter::class)
                ->label(__('Export to Excel'))
                ->modalHeading(__('Export tournament participants'))
                ->modalDescription(__('Select a tournament to export all registered participants with their full profile data.'))
                ->formats([ExportFormat::Xlsx])
                ->columnMapping(false)
                ->modifyQueryUsing(
                    fn (Builder $query, array $options): Builder => ParticipantExportSupport::queryForTournament(
                        (int) $options['tournament_id'],
                    ),
                ),
            Actions\CreateAction::make(),
        ];
    }
}
