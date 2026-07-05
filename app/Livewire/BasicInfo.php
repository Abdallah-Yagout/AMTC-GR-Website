<?php

namespace App\Livewire;

class BasicInfo extends ProfileSectionForm
{
    protected function sectionKey(): string
    {
        return 'basic-info';
    }

    public function mount(): void
    {
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
        return view('livewire.basic-info', $this->sectionViewData());
    }
}
