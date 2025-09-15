<?php

namespace Digipemad\Sia\Academic\Policies;

use Modules\Account\Models\User;
use Digipemad\Sia\Academic\Models\Academic;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportAcademicPolicy
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
        return $user->hasAnyPermissionsTo(['read-academic-reports', 'write-academic-reports', 'delete-academic-reports']);
    }

    /**
     * Can show.
     */
    public function show(User $user)
    {
        return $user->hasAnyPermissionsTo(['read-academic-reports']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-academic-reports']);
    }

    /**
     * Can update.
     */
    public function update(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-academic-reports']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-academic-reports']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user)
    {
        return $user->hasAnyPermissionsTo(['delete-academic-reports']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user)
    {
        return $user->hasAnyPermissionsTo(['delete-academic-reports']);
    }
}
