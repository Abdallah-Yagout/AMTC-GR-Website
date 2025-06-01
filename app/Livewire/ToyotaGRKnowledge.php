<?php

namespace App\Livewire;

use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Livewire\Component;

class ToyotaGRKnowledge extends UpdateProfileInformationForm
{
    public function mount()
    {
        parent::mount();
        // Add your logic here
        $user = auth()->user();
        $this->state['toyota_gr_knowledge'] = $user->profile->toyota_gr_knowledge;
        $this->state['favorite_car'] = $user->profile->favorite_car;

    }
    public function render()
    {
        return view('livewire.toyota-g-r-knowledge');
    }
}
