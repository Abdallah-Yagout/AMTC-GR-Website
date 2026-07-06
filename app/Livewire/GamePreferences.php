<?php

namespace App\Livewire;

class GamePreferences extends ProfileSectionForm
{
    protected function sectionKey(): string
    {
        return 'game-preferences';
    }

    public function mount(): void
    {
        parent::mount();

        $user = auth()->user();
        $this->state['favorite_games'] = $user->profile?->favorite_games ?? '';
        $this->state['gt7_ranking'] = $user->profile?->gt7_ranking ?? '';
    }

    public function render()
    {
        return view('livewire.game-preferences');
    }
}
