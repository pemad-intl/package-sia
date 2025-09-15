<?php

namespace Digipemad\Sia\Counseling;

use Modules\Admin\Models;
use Digipemad\Sia\Counseling\Policies;
use Digipemad\Sia\Academic\Models as ACDModel;
use Digipemad\Sia\Administration\Policies as AdmPolicies;
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
        ACDModel\StudentSemesterCounseling::class => Policies\CounselingPolicy::class,
        ACDModel\AcademicClassroomPresence::class => Policies\CounselingPresencePolicy::class,
        ACDModel\AcademicCaseCategoryDescription::class => Policies\CounselingCaseDescriptionPolicy::class,
        ACDModel\AcademicCaseCategory::class => Policies\CounselingCaseCategoryPolicy::class,
        ACDModel\StudentSemesterCounseling::class => Policies\CounselingPolicy::class,
        ACDModel\AcademicCounselingCategory::class => Policies\CounselingCategoryPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        // Gate::define(
        //     'admin::access',
        //     fn(User $user) => count(array_filter(array_map(fn($policy) => (new $policy())->access($user), $this->policies)))
        // );

        // Gate::define(
        //     'client::access',
        //     fn (User $user) => count(array_filter(array_map(fn ($policy) => (new $policy())->access($user), $this->policies)))
        // );

        Gate::define('counseling::access', function(User $user){
             $employee = $user->regularEmp;

            if ($employee && $employee->contract) {
                $positionType = $employee->position->position->type;

                if ($positionType === \Modules\Core\Enums\PositionTypeEnum::GURUBK) {
                    return true;
                }
            }

            return false;
        });

        // Gate::define(
        //     'counseling::access',
        //     fn(User $user) => count(array_filter(array_map(fn($policy) => (new $policy())->access($user), $this->policies))) || $user->can('access', User::class)
        // );
    }
}
