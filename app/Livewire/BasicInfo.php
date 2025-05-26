<?php

namespace App\Livewire;

use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Livewire\Component;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;

class BasicInfo extends UpdateProfileInformationForm
{
    public function mount()
    {
        parent::mount();
        // Add your logic here
        $user = auth()->user();


        $this->state['birthdate'] = optional($user->profile)->birthdate
            ? \Carbon\Carbon::parse($user->profile->birthdate)->format('Y-m-d')
            : null;

    }
    public function render()
    {

        return view('livewire.basic-info');
    }
}
