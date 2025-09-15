<?php

namespace Digipemad\Sia\Administration\Policies;

use Modules\Account\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BillBatchPolicy
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
        return $user->hasAnyPermissionsTo(['read-bill-batches', 'write-bill-batches', 'delete-bill-batches']);
    }

    /**
     * Can show.
     */
    public function show(User $user)
    {
        return $user->hasAnyPermissionsTo(['read-bill-batches']);
    }

    /**
     * Can store.
     */
    public function store(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-bill-batches']);
    }

    /**
     * Can update.
     */
    public function update(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-bill-batches']);
    }

    /**
     * Can destroy.
     */
    public function destroy(User $user)
    {
        return $user->hasAnyPermissionsTo(['write-bill-batches']);
    }

    /**
     * Can restore.
     */
    public function restore(User $user)
    {
        return $user->hasAnyPermissionsTo(['delete-bill-batches']);
    }

    /**
     * Can kill.
     */
    public function kill(User $user)
    {
        return $user->hasAnyPermissionsTo(['delete-bill-batches']);
    }
}
