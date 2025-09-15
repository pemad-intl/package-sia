<?php

namespace Digipemad\Sia\Administration\Policies;

use Modules\Account\Models\User;
use Modules\Account\Models\UserAppreciation;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarAppreciationPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    /**
     * Can access page.
     */
    public function access(User $user)
    {
        return $user->hasAnyPermissionsTo(['read-scholar-appreciations', 'write-scholar-appreciations', 'delete-scholar-appreciations']);
    }

    /**
     * Can show.
     */
    public function show(User $user)
    {
        return $user->hasAnyPermissionsTo(['read-scholar-appreciations']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-scholar-appreciations']);
    }

    /**
     * Can update.
     */
    public function update(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-scholar-appreciations']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-scholar-appreciations']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user)
    {
        return $user->hasAnyPermissionsTo(['delete-scholar-appreciations']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user)
    {
        return $user->hasAnyPermissionsTo(['delete-scholar-appreciations']);
    }
}
