<?php

namespace App\Livewire;

use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Livewire\Component;

class GamePreferences extends UpdateProfileInformationForm
{
    public function mount()
    {
        parent::mount();
        // Add your logic here
        $user = auth()->user();


        $this->state['favorite_games'] = $user->profile?->favorite_games ?? '';
        $this->state['gt7_ranking'] = $user->profile?->gt7_ranking ?? '';

    }
    public function render()
    {
        return view('livewire.game-preferences');
    }
}
