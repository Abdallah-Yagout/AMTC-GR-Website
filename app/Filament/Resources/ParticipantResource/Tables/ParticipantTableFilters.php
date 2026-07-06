<?php

namespace App\Filament\Resources\ParticipantResource\Tables;

use App\Helpers\Location;
use App\Models\Participant;
use App\Models\Tournament;
use App\Support\ParticipantExportSupport;
use App\Support\ProfileFormOptions;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;

final class ParticipantTableFilters
{
    /** @return array<int, mixed> */
    public static function make(): array
    {
        return [
            SelectFilter::make('tournament_id')
                ->label('Tournament')
                ->options(ParticipantExportSupport::tournamentOptions())
                ->searchable()
                ->indicateUsing(function (array $data): ?string {
                    if (blank($data['value'] ?? null)) {
                        return null;
                    }

                    $tournament = Tournament::query()->find($data['value']);

                    if ($tournament === null) {
                        return null;
                    }

                    $title = is_array($tournament->title)
                        ? ($tournament->title[app()->getLocale()] ?? reset($tournament->title) ?: null)
                        : $tournament->title;

                    return filled($title) ? (string) $title : null;
                }),

            SelectFilter::make('location')
                ->label('Location')
                ->options(self::locationOptions())
                ->searchable(),

            Filter::make('registered_between')
                ->label('Registered Between')
                ->form([
                    DatePicker::make('registered_from')->label('From'),
                    DatePicker::make('registered_until')->label('Until'),
                ])
                ->columns(2)
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['registered_from'] ?? null,
                            fn (Builder $query, string $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['registered_until'] ?? null,
                            fn (Builder $query, string $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                }),

            SelectFilter::make('city')
                ->label('City')
                ->options(Location::cities())
                ->query(self::profileSelectQuery('city')),

            SelectFilter::make('gender')
                ->label('Gender')
                ->options(ProfileFormOptions::for('gender'))
                ->query(self::profileSelectQuery('gender')),

            SelectFilter::make('skill_level')
                ->label('Skill Level')
                ->options(ProfileFormOptions::for('skill_level'))
                ->query(self::profileSelectQuery('skill_level')),

            SelectFilter::make('primary_platform')
                ->label('Primary Platform')
                ->options(ProfileFormOptions::for('primary_platform'))
                ->query(self::profileSelectQuery('primary_platform')),

            SelectFilter::make('weekly_hours')
                ->label('Weekly Hours')
                ->options(ProfileFormOptions::for('weekly_hours'))
                ->query(self::profileSelectQuery('weekly_hours')),

            SelectFilter::make('gt7_ranking')
                ->label('GT7 Ranking')
                ->options(ProfileFormOptions::for('gt7_ranking'))
                ->query(self::profileSelectQuery('gt7_ranking')),

            SelectFilter::make('toyota_gr_knowledge')
                ->label('Toyota GR Knowledge')
                ->options(ProfileFormOptions::for('toyota_gr_knowledge'))
                ->query(self::profileSelectQuery('toyota_gr_knowledge')),

            SelectFilter::make('heard_about')
                ->label('Heard About')
                ->options(ProfileFormOptions::for('heard_about'))
                ->query(self::profileSelectQuery('heard_about')),

            SelectFilter::make('preferred_time')
                ->label('Preferred Time')
                ->options(ProfileFormOptions::for('preferred_time'))
                ->query(self::profileSelectQuery('preferred_time')),

            TernaryFilter::make('has_ps5')
                ->label('Owns PS5')
                ->queries(
                    true: fn (Builder $query): Builder => $query->whereHas(
                        'profile',
                        fn (Builder $profileQuery): Builder => $profileQuery->where('has_ps5', true),
                    ),
                    false: fn (Builder $query): Builder => $query->whereHas(
                        'profile',
                        fn (Builder $profileQuery): Builder => $profileQuery->where('has_ps5', false),
                    ),
                    blank: fn (Builder $query): Builder => $query,
                ),

            TernaryFilter::make('participated_before')
                ->label('Participated in AMTC 2024')
                ->queries(
                    true: fn (Builder $query): Builder => $query->whereHas(
                        'profile',
                        fn (Builder $profileQuery): Builder => $profileQuery->where('participated_before', true),
                    ),
                    false: fn (Builder $query): Builder => $query->whereHas(
                        'profile',
                        fn (Builder $profileQuery): Builder => $profileQuery->where('participated_before', false),
                    ),
                    blank: fn (Builder $query): Builder => $query,
                ),

            TernaryFilter::make('wants_training')
                ->label('Wants Training')
                ->queries(
                    true: fn (Builder $query): Builder => $query->whereHas(
                        'profile',
                        fn (Builder $profileQuery): Builder => $profileQuery->where('wants_training', true),
                    ),
                    false: fn (Builder $query): Builder => $query->whereHas(
                        'profile',
                        fn (Builder $profileQuery): Builder => $profileQuery->where('wants_training', false),
                    ),
                    blank: fn (Builder $query): Builder => $query,
                ),

            TernaryFilter::make('join_whatsapp')
                ->label('Join WhatsApp')
                ->queries(
                    true: fn (Builder $query): Builder => $query->whereHas(
                        'profile',
                        fn (Builder $profileQuery): Builder => $profileQuery->where('join_whatsapp', true),
                    ),
                    false: fn (Builder $query): Builder => $query->whereHas(
                        'profile',
                        fn (Builder $profileQuery): Builder => $profileQuery->where('join_whatsapp', false),
                    ),
                    blank: fn (Builder $query): Builder => $query,
                ),

            SelectFilter::make('profile_complete')
                ->label('Profile Complete')
                ->options([
                    'complete' => 'Complete',
                    'incomplete' => 'Incomplete',
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return match ($data['value'] ?? null) {
                        'complete' => $query->whereHas(
                            'profile',
                            fn (Builder $profileQuery): Builder => $profileQuery->registrationComplete(),
                        ),
                        'incomplete' => $query->where(function (Builder $inner): void {
                            $inner
                                ->whereDoesntHave('profile')
                                ->orWhereHas(
                                    'profile',
                                    fn (Builder $profileQuery): Builder => $profileQuery->registrationIncomplete(),
                                );
                        }),
                        default => $query,
                    };
                }),
        ];
    }

    public static function layout(): FiltersLayout
    {
        return FiltersLayout::AboveContentCollapsible;
    }

    /** @return array<string, string> */
    private static function locationOptions(): array
    {
        return Participant::query()
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->distinct()
            ->orderBy('location')
            ->pluck('location', 'location')
            ->all();
    }

    private static function profileSelectQuery(string $field): \Closure
    {
        return function (Builder $query, array $data) use ($field): Builder {
            if (blank($data['value'] ?? null)) {
                return $query;
            }

            return $query->whereHas(
                'profile',
                fn (Builder $profileQuery): Builder => $profileQuery->where($field, $data['value']),
            );
        };
    }
}
