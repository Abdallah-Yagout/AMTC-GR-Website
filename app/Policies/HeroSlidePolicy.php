<?php

namespace App\Policies;

use App\Models\HeroSlide;
use App\Models\User;

class HeroSlidePolicy
{
    private function isAdmin(User $user): bool
    {
        return (int) $user->type === 1;
    }

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function view(User $user, HeroSlide $heroSlide): bool
    {
        return $this->isAdmin($user);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, HeroSlide $heroSlide): bool
    {
        return $this->isAdmin($user);
    }

    public function delete(User $user, HeroSlide $heroSlide): bool
    {
        return $this->isAdmin($user);
    }

    public function restore(User $user, HeroSlide $heroSlide): bool
    {
        return $this->isAdmin($user);
    }

    public function forceDelete(User $user, HeroSlide $heroSlide): bool
    {
        return $this->isAdmin($user);
    }
}
