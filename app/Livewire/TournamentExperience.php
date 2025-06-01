<?php

namespace App\Livewire;

use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Livewire\Component;

class TournamentExperience extends UpdateProfileInformationForm
{
    public function mount()
    {
        parent::mount();
        // Add your logic here
        $user = auth()->user();
        $this->state['participated_before'] = (bool)($user->profile?->participated_before ?? false);
        $this->state['wants_training'] = (bool)($user->profile?->wants_training ?? false);
        $this->state['join_whatsapp'] = (bool)($user->profile?->join_whatsapp ?? false);


    }
    public function render()
    {
        return view('livewire.tournament-experience');
    }
}
