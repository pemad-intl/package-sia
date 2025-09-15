<?php

namespace Digipemad\Sia\Administration\Policies;

use Modules\Account\Models\User;
use Modules\Academic\Models\AcademicClassroom;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarClassroomPolicy
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
        return $user->hasAnyPermissionsTo(['read-scholar-classrooms', 'write-scholar-classrooms', 'delete-scholar-classrooms']);
    }

    /**
     * Can show.
     */
    public function show(User $user)
    {
        return $user->hasAnyPermissionsTo(['read-scholar-classrooms']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-scholar-classrooms']);
    }

    /**
     * Can update.
     */
    public function update(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-scholar-classrooms']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-scholar-classrooms']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user)
    {
        return $user->hasAnyPermissionsTo(['delete-scholar-classrooms']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user)
    {
        return $user->hasAnyPermissionsTo(['delete-scholar-classrooms']);
    }
}
