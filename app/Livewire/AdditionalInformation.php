<?php

namespace App\Livewire;

use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Livewire\Component;

class AdditionalInformation extends UpdateProfileInformationForm
{
    public function mount()
    {
        parent::mount();
        // Add your logic here
        $user = auth()->user();
        $this->state['heard_about'] = $user->profile?->heard_about ?? '';
        $this->state['preferred_time'] = $user->profile?->preferred_time ?? '';
        $this->state['suggestions'] = $user->profile?->suggestions ?? '';
        $this->state['motivation'] = $user->profile?->motivation ?? '';



    }
    public function render()
    {
        return view('livewire.additional-information');
    }
}
