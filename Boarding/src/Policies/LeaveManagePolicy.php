<?php

namespace Digipemad\Sia\Boarding\Policies;

use Modules\Account\Models\User;
use Modules\Account\Models\UserAchievement;
use Digipemad\Sia\Boarding\Models\BoardingStudentsLeave;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveManagePolicy
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
        return $user->hasAnyPermissionsTo(['read-leave-manages', 'write-leave-manages', 'delete-leave-manages']);
    }

    /**
     * Can show.
     */
    public function show(User $user)
    {
        return $user->hasAnyPermissionsTo(['read-leave-manages']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-leave-manages']);
    }

    /**
     * Can update.
     */
    public function update(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-leave-manages']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-leave-manages']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user)
    {
        return $user->hasAnyPermissionsTo(['delete-leave-manages']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user)
    {
        return $user->hasAnyPermissionsTo(['delete-leave-manages']);
    }
}
