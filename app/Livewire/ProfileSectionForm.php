<?php

namespace App\Livewire;

use App\Support\ProfileCompletion;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Livewire\Attributes\Computed;

abstract class ProfileSectionForm extends UpdateProfileInformationForm
{
    abstract protected function sectionKey(): string;

    public function updateProfileInformation(UpdatesUserProfileInformation $updater): void
    {
        parent::updateProfileInformation($updater);

        $this->user->unsetRelation('profile');
        unset($this->sectionComplete);

        $this->dispatch('profile-updated');
    }

    #[Computed]
    public function sectionComplete(): bool
    {
        return ProfileCompletion::for($this->user->profile)
            ->isSectionComplete($this->sectionKey());
    }
}
