<?php

namespace App\Livewire;

use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Livewire\Component;

class GamingExperience extends UpdateProfileInformationForm
{
    public function mount()
    {
        parent::mount();
        // Add your logic here
        $user = auth()->user();


        $this->state['skill_level'] = $user->profile?->skill_level ?? '';
        $this->state['has_ps5'] = (bool)($user->profile?->has_ps5 ?? false);
        $this->state['primary_platform'] = $user->profile?->primary_platform ?? '';
        $this->state['weekly_hours'] = $user->profile?->weekly_hours ?? 0;


    }
    public function render()
    {
        return view('livewire.gaming-experience');
    }
}
