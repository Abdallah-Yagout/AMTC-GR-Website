<?php

namespace App\Livewire;

class AdditionalInformation extends ProfileSectionForm
{
    protected function sectionKey(): string
    {
        return 'additional-information';
    }

    public function mount(): void
    {
        parent::mount();

        $user = auth()->user();
        $this->state['heard_about'] = $user->profile?->heard_about ?? '';
        $this->state['preferred_time'] = $user->profile?->preferred_time ?? '';
        $this->state['suggestions'] = $user->profile?->suggestions ?? '';
        $this->state['motivation'] = $user->profile?->motivation ?? '';
    }

    public function render()
    {
        return view('livewire.additional-information', $this->sectionViewData());
    }
}
