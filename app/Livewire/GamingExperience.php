<?php

namespace App\Livewire;

class GamingExperience extends ProfileSectionForm
{
    protected function sectionKey(): string
    {
        return 'gaming-experience';
    }

    public function mount(): void
    {
        parent::mount();

        $user = auth()->user();
        $this->state['skill_level'] = $user->profile?->skill_level ?? '';
        $this->state['has_ps5'] = (bool) ($user->profile?->has_ps5 ?? false);
        $this->state['primary_platform'] = $user->profile?->primary_platform ?? '';
        $this->state['weekly_hours'] = $user->profile?->weekly_hours ?? 0;
    }

    public function render()
    {
        return view('livewire.gaming-experience', $this->sectionViewData());
    }
}
