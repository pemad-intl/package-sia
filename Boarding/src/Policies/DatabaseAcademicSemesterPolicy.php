<?php

namespace Digipemad\Sia\Administration\Policies;

use Modules\Account\Models\User;
use Digipemad\Sia\Academic\Models\AcademicSemester;
use Illuminate\Auth\Access\HandlesAuthorization;

class DatabaseAcademicSemesterPolicy
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
        return $user->hasAnyPermissionsTo(['read-database-academic-semesters', 'write-database-academic-semesters', 'delete-database-academics-semesters']);
    }

    /**
     * Can show.
     */
    public function show(User $user)
    {
        return $user->hasAnyPermissionsTo(['read-database-academic-semesters']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-database-academic-semesters']);
    }

    /**
     * Can update.
     */
    public function update(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-database-academic-semesters']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-database-academic-semesters']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user)
    {
        return $user->hasAnyPermissionsTo(['delete-database-academic-semesters']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user)
    {
        return $user->hasAnyPermissionsTo(['delete-database-academic-semesters']);
    }
}
