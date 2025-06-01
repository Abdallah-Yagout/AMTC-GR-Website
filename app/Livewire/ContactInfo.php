<?php

namespace App\Livewire;

use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Livewire\Component;

class ContactInfo extends UpdateProfileInformationForm
{
    public function mount()
    {
        parent::mount();
        // Add your logic here
        $user = auth()->user();


        $this->state['whatsapp'] = $user->profile->whatsapp;

    }
    public function render()
    {
        return view('livewire.contact-info');
    }
}
