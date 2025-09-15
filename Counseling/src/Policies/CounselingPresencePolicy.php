<?php

namespace Digipemad\Sia\Counseling\Policies;

use Modules\Account\Models\User;
use Digipemad\Sia\Academic\Models\AcademicClassroomPresence;
use Illuminate\Auth\Access\HandlesAuthorization;

class CounselingPresencePolicy
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
        return $user->hasAnyPermissionsTo(['read-counseling-presences', 'write-counseling-presences', 'delete-counseling-presences']);
    }

    /**
     * Can show.
     */
    public function show(User $user, AcademicClassroomPresence $model)
    {
        return $user->hasAnyPermissionsTo(['read-counseling-presences']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-counseling-presences']);
    }

    /**
     * Can update.
     */
    public function update(User $user, AcademicClassroomPresence $model)
    {
        return $user->hasAnyPermissionsTo(['write-counseling-presences']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-counseling-presences']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user, AcademicClassroomPresence $model)
    {
        return $user->hasAnyPermissionsTo(['delete-counseling-presences']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user, AcademicClassroomPresence $model)
    {
        return $user->hasAnyPermissionsTo(['delete-counseling-presences']);
    }
}
