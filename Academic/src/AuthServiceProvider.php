<?php

namespace Digipemad\Sia\Academic;

use Digipemad\Academic\Models;
use Digipemad\Academic\Policies;
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
        Models\Academic::class => Policies\ReportAcademicPolicy::class,
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

        //Gate::define('student::access', fn(User $user) => true);
        // Gate::define(
        //     'teacher::access',
        //     fn(User $user) => count(array_filter(array_map(fn($policy) => (new $policy())->access($user), $this->policies)))
        // );

        // Gate::define(
        //     'client::access',
        //     fn (User $user) => count(array_filter(array_map(fn ($policy) => (new $policy())->access($user), $this->policies)))
        // );
        Gate::define('academic::access', function(User $user){
            $student = $user->student()->first();

            if (!empty($student)) {
                return true;
            }

            return false;
        });
    }
}
