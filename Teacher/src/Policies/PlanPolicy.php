<?php

namespace Digipemad\Sia\Administration\Policies;

use Modules\Account\Models\User;
use Digipemad\Sia\Academic\Models\AcademicSubjectMeetPlan;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlanPolicy
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
        return $user->hasAnyPermissionsTo(['read-teacher-plans', 'write-teacher-plans', 'delete-teacher-plans']);
    }

    /**
     * Can show.
     */
    public function show(User $user)
    {
        return $user->hasAnyPermissionsTo(['read-teacher-plans']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-teacher-plans']);
    }

    /**
     * Can update.
     */
    public function update(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-teacher-plans']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-teacher-plans']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user)
    {
        return $user->hasAnyPermissionsTo(['delete-teacher-plans']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user)
    {
        return $user->hasAnyPermissionsTo(['delete-teacher-plans']);
    }
}
