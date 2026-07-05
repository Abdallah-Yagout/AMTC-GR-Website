<?php

namespace App\Livewire;

use App\Support\ProfileCompletion;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;

abstract class ProfileSectionForm extends UpdateProfileInformationForm
{
    abstract protected function sectionKey(): string;

    public function updateProfileInformation(UpdatesUserProfileInformation $updater): void
    {
        parent::updateProfileInformation($updater);

        $this->dispatch('profile-updated');
    }

    protected function sectionComplete(): bool
    {
        return ProfileCompletion::for(auth()->user()?->profile)
            ->isSectionComplete($this->sectionKey());
    }

    /**
     * @return array<string, mixed>
     */
    protected function sectionViewData(): array
    {
        return [
            'sectionComplete' => $this->sectionComplete(),
        ];
    }
}
