<?php

namespace App\Policies;

use App\Models\User;
use MoonShine\Models\MoonshineUser;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function before(MoonshineUser $user, string $ability): bool|null
    {
        if ($user->moonshineUserRole->name == 'Admin') {
            return true;
        }
        return null;
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(MoonshineUser $user): bool
    {
        return $user->moonshineUserRole->name == 'Guard';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(MoonshineUser $user, User $model): bool
    {
        return $user->moonshineUserRole->name == 'Guard';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(MoonshineUser $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(MoonshineUser $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(MoonshineUser $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(MoonshineUser $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(MoonshineUser $user, User $model): bool
    {
        return false;
    }
}
