<?php

namespace App\Livewire;

use App\Support\ProfileCompletion;
use Livewire\Attributes\On;
use Livewire\Component;

class ProfileCompletionBanner extends Component
{
    public int $percentage = 0;

    public int $completedCount = 0;

    public int $totalCount = 0;

    public int $remainingCount = 0;

    public bool $isComplete = false;

    public function mount(): void
    {
        $this->refreshCompletion();
    }

    #[On('profile-updated')]
    public function refreshCompletion(): void
    {
        $completion = ProfileCompletion::for(auth()->user()?->profile);

        $this->percentage = $completion->percentage();
        $this->completedCount = $completion->completedCount();
        $this->totalCount = $completion->totalCount();
        $this->remainingCount = $completion->remainingCount();
        $this->isComplete = $completion->isComplete();
    }

    public function render()
    {
        return view('livewire.profile-completion-banner');
    }
}
