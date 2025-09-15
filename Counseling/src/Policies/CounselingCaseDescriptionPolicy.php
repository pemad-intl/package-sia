<?php

namespace Digipemad\Sia\Counseling\Policies;

use Modules\Account\Models\User;
use Digipemad\Sia\Academic\Models\StudentSemesterCase;
use Illuminate\Auth\Access\HandlesAuthorization;

class CounselingCaseDescriptionPolicy
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
        return $user->hasAnyPermissionsTo(['read-counseling-case-descriptions', 'write-counseling-case-descriptions', 'delete-counseling-case-descriptions']);
    }

    /**
     * Can show.
     */
    public function show(User $user, StudentSemesterCase $model)
    {
        return $user->hasAnyPermissionsTo(['read-counseling-case-descriptions']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-counseling-case-descriptions']);
    }

    /**
     * Can update.
     */
    public function update(User $user, StudentSemesterCase $model)
    {
        return $user->hasAnyPermissionsTo(['write-counseling-case-descriptions']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user, StudentSemesterCase $model)
    {
        return $user->hasAnyPermissionsTo(['write-counseling-case-descriptions']);
    }
}
