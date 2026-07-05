<?php

namespace App\Livewire;

class ContactInfo extends ProfileSectionForm
{
    protected function sectionKey(): string
    {
        return 'contact-info';
    }

    public function mount(): void
    {
        parent::mount();

        $user = auth()->user();
        $this->state['whatsapp'] = $user->profile?->whatsapp ?? '';
    }

    public function render()
    {
        return view('livewire.contact-info', $this->sectionViewData());
    }
}
