<?php

namespace App\Policies;

use App\Models\FootballField;
use App\Models\User;

class FootballFieldPolicy
{
    public function before(User $user): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, FootballField $footballField): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, FootballField $footballField): bool
    {
        return false;
    }

    public function delete(User $user, FootballField $footballField): bool
    {
        return false;
    }
}
