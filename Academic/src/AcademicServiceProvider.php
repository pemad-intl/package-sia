<?php

namespace Digipemad\Sia\Academic;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Blade;

class AcademicServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Academic';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'academic';

    public function boot()
    {
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->loadDynamicRelationships();
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');


        $this->loadViewsFrom(__DIR__ . '/Resources/Views', $this->moduleNameLower);
        $this->loadViewsFrom(__DIR__ . '/Resources/Components', 'x-' . $this->moduleNameLower);

        Blade::componentNamespace('Digipemad\\Sia\\' . $this->moduleName . '\\Resources\\Components', $this->moduleNameLower);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/' . $this->moduleNameLower . '.php',
            'modules.' . $this->moduleNameLower
        );
    }

    public function loadDynamicRelationships()
    {
        // User::resolveRelationUsing('employee', function ($user) {
        //     return $user->hasOne(Employee::class, 'user_id')->withDefault();
        // });

        // Position::resolveRelationUsing('employees', function ($position) {
        //     return $position->belongsToMany(Employee::class, 'empl_positions', 'position_id', 'empl_id')->withPivot('id');
        // });

        // Position::resolveRelationUsing('employeePositions', function ($position) {
        //     return $position->hasMany(EmployeePosition::class, 'position_id');
        // });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
}
