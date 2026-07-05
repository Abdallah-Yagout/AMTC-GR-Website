<?php

namespace App\Livewire;

class TournamentExperience extends ProfileSectionForm
{
    protected function sectionKey(): string
    {
        return 'tournament-experience';
    }

    public function mount(): void
    {
        parent::mount();

        $user = auth()->user();
        $this->state['participated_before'] = (bool) ($user->profile?->participated_before ?? false);
        $this->state['wants_training'] = (bool) ($user->profile?->wants_training ?? false);
        $this->state['join_whatsapp'] = (bool) ($user->profile?->join_whatsapp ?? false);
    }

    public function render()
    {
        return view('livewire.tournament-experience', $this->sectionViewData());
    }
}
