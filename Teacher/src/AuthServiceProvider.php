<?php

namespace Digipemad\Sia\Teacher;

use Digipemad\Sia\Admin\Models;
use Digipemad\Sia\Teacher\Policies;
use Digipemad\Sia\Administration\Policies as AdmPolicies;
use Digipemad\Sia\Academic\Models as ACmodel;
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
        ACmodel\Academic::class => AdmPolicies\DatabaseAcademicPolicy::class,
        ACmodel\AcademicSemester::class => AdmPolicies\CurriculaMeetPolicy::class,
        ACmodel\StudentSemesterCase::class => Policies\StudentSemesterCasePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        //Gate::define('teacher::access', fn(User $user) => true);
        // Gate::define(
        //     'admin::access',
        //     fn(User $user) => count(array_filter(array_map(fn($policy) => (new $policy())->access($user), $this->policies)))
        // );

        Gate::define('teacher::access', function (User $user) {
            $employee = $user->teacher;

            $positionType = $employee->position->position->type;

            if (
                $positionType === \Digipemad\Sia\Core\Enums\PositionTypeEnum::HUMAS &&
                !is_null($employee->classroom)
            ) {
                return true;
            }

            if ($positionType === \Digipemad\Sia\Core\Enums\PositionTypeEnum::GURU) {
                return true;
            }

            return false;
        });

    }
}
