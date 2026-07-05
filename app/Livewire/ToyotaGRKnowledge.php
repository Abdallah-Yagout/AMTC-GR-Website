<?php

namespace App\Livewire;

class ToyotaGRKnowledge extends ProfileSectionForm
{
    protected function sectionKey(): string
    {
        return 'toyota-g-r-knowledge';
    }

    public function mount(): void
    {
        parent::mount();

        $user = auth()->user();
        $this->state['toyota_gr_knowledge'] = $user->profile?->toyota_gr_knowledge ?? '';
        $this->state['favorite_car'] = $user->profile?->favorite_car ?? '';
    }

    public function render()
    {
        return view('livewire.toyota-g-r-knowledge', $this->sectionViewData());
    }
}
