<?php

namespace Digipemad\Sia\Boarding;

use Digipemad\Sia\Academic\Models as ACmodel;
use Digipemad\Sia\Administration\Models as ADmodel;
use Digipemad\Sia\Boarding\Models as Board;
use Digipemad\Sia\Boarding\Policies;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Account\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Board\BoardingStudentsLeave::class => Policies\LeaveManagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        // Gate::define(
        //     'administration::access',
        //     fn(User $user) => count(array_filter(array_map(fn($policy) => (new $policy())->access($user), $this->policies)))
        // );

        Gate::define('boarding::access', function(User $user) {
            $employee = $user->regularEmp;

            if (
                $employee &&
                $employee->contract
            ) {
                $positionType = $employee->position->position->type;

                $allowedTypes = [
                    \Modules\Core\Enums\PositionTypeEnum::PENGASUH,
                    \Modules\Core\Enums\PositionTypeEnum::PENGURUS,
                    \Modules\Core\Enums\PositionTypeEnum::USTADZ,
                    \Modules\Core\Enums\PositionTypeEnum::USTADZAH,
                    \Modules\Core\Enums\PositionTypeEnum::ADMINPONDOK
                ];


                return in_array($positionType, $allowedTypes, true);
            }

            return false;
        });

 //       Gate::define('boarding::access', fn(User $user) => true);
        // Gate::define(
        //     'boarding::access',
        //     fn(User $user) => count(array_filter(array_map(fn($policy) => (new $policy())->access($user), $this->policies))) || $user->can('access', User::class)
        // );
    }
}
