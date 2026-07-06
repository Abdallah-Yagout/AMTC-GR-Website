<?php

namespace App\Filament\Exports;

use App\Models\Participant;
use App\Support\ParticipantExportRow;
use App\Support\ParticipantExportSupport;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class ParticipantExporter extends Exporter
{
    protected static ?string $model = Participant::class;

    public static function getColumns(): array
    {
        return collect(ParticipantExportRow::columnLabels())
            ->map(
                fn (string $label, string $name): ExportColumn => ExportColumn::make($name)
                    ->label($label)
                    ->state(fn (Participant $record): string => ParticipantExportRow::from($record)->toArray()[$name] ?? '')
            )
            ->values()
            ->all();
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            ParticipantExportSupport::tournamentSelect(),
        ];
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->with(['user', 'profile', 'tournament']);
    }

    public function getJobConnection(): ?string
    {
        return 'sync';
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $count = number_format($export->successful_rows);

        return "Your participant export of {$count} ".str('row')->plural($export->successful_rows).' is ready to download.';
    }

    public function getFileName(Export $export): string
    {
        $tournamentId = $this->getOptions()['tournament_id'] ?? null;

        return 'participants-'
            .ParticipantExportSupport::exportFileNameSlug(
                filled($tournamentId) ? (int) $tournamentId : null,
            )
            .'-'.now()->format('Y-m-d-His');
    }
}
