<?php

namespace App\Policies;

use App\Models\Agreement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AgreementPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Agreement $agreement): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Agreement $agreement): bool
    {
        return $agreement->status === 'draft';
    }

    public function delete(User $user, Agreement $agreement): bool
    {
        return $agreement->status === 'draft';
    }
}
