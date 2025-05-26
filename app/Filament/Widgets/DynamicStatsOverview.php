<?php

namespace App\Filament\Widgets;

use Filament\Forms\Components\Grid;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class DynamicStatsOverview extends Widget implements HasForms
{
    use \Filament\Forms\Concerns\InteractsWithForms;

    public ?string $tournament = null;


    public function mount(): void
    {
        // Optionally set default tournament
        $this->tournament = request('tournament_id') ?? '';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tournament')
                    ->label('Select Tournament')
                    ->options(\App\Models\Tournament::pluck('title', 'id'))
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(fn ($state) => $this->tournament = $state)
                    ->default($this->tournament),
            ]);
    }



    public function render(): View
    {
        $locationStats = collect();
        $genderStats = collect();
        $skillLevelStats = collect();
        $totalParticipants = 0;

        if ($this->tournament) {
            // Using Eloquent relationships
            $tournament = \App\Models\Tournament::with(['participants.user.profile'])
                ->find($this->tournament);

            if ($tournament) {
                // Location Stats
                $locationStats = $tournament->participants
                    ->groupBy('profile.city')
                    ->map(function ($group, $city) {
                        return (object) [
                            'city' => $city ?: 'Unknown',
                            'total' => $group->count()
                        ];
                    })
                    ->sortByDesc('total')
                    ->values();
                // Gender Stats

                $genderStats = $tournament->participants
                    ->groupBy('profile.gender')
                    ->map(function ($group, $gender) {
                        return (object) [
                            'gender' => $gender ?: 'Unknown',
                            'total' => $group->count()
                        ];
                    })
                    ->sortByDesc('total')
                    ->values();

                // Skill Level Stats
                $skillLevelStats = $tournament->participants
                    ->groupBy('profile.skill_level')
                    ->map(function ($group, $skillLevel) {
                        return (object) [
                            'skill_level' => $skillLevel ?: 'Unknown',
                            'total' => $group->count()
                        ];
                    })
                    ->sortByDesc('total')
                    ->values();

                // Total Participants
                $totalParticipants = $tournament->participants->count();
            }
        }

        return view('filament.widgets.dynamic-stats-overview', [
            'locationStats' => $locationStats,
            'genderStats' => $genderStats,
            'skillLevelStats' => $skillLevelStats,
            'totalParticipants' => $totalParticipants,
        ]);
    }
}
