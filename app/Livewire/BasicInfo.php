<?php

namespace App\Livewire;

use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;

class BasicInfo extends UpdateProfileInformationForm
{
    public function mount()
    {
        //        parent::mount();
        // Add your logic here
        $user = auth()->user();

        $birthdate = $user->profile?->birthdate;
        $this->state['birthdate'] = $birthdate
            ? \Carbon\Carbon::parse($birthdate)->format('Y-m-d')
            : null;
        $this->state['city'] = $user->profile?->city ?? '';
        $this->state['name'] = $user->name ?? '';
        $this->state['email'] = $user->email ?? '';
        $this->state['gender'] = $user->profile?->gender ?? '';

    }

    public function render()
    {

        return view('livewire.basic-info');
    }
}
