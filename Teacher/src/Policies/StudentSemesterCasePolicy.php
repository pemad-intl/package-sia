<?php

namespace Digipemad\Sia\Teacher\Policies;

use Modules\Account\Models\User;
use Digipemad\Sia\Academic\Models\AcademicSubjectMeetPlan;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentSemesterCasePolicy
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
        return $user->hasAnyPermissionsTo(['read-student-semester-cases', 'write-student-semester-cases', 'delete-student-semester-cases']);
    }

    /**
     * Can show.
     */
    public function show(User $user)
    {
        return $user->hasAnyPermissionsTo(['read-student-semester-cases']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-student-semester-cases']);
    }

    /**
     * Can update.
     */
    public function update(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-student-semester-cases']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-student-semester-cases']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user)
    {
        return $user->hasAnyPermissionsTo(['delete-student-semester-cases']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user)
    {
        return $user->hasAnyPermissionsTo(['delete-student-semester-cases']);
    }
}
