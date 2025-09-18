<?php

namespace Digipemad\Sia\Administration;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Blade;
use App\Services\SidebarManager;
use Modules\Account\Models\User;
use Digipemad\Sia\Administration\Models\School;
use Digipemad\Sia\Administration\Models\Traits\Account\UserTrait as AdministrationTrait;

class AdministrationServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Administration';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'administration';

    public function boot(SidebarManager $sidebar)
    {
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        
        // User::mixin(new class {
        //     use AdministrationTrait;
        // });

        $this->loadDynamicRelationships();
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');


        $this->loadViewsFrom(__DIR__ . '/Resources/Views', $this->moduleNameLower);
        $this->loadViewsFrom(__DIR__ . '/Resources/Components', 'x-' . $this->moduleNameLower);

        $sidebar->addMenu([
            'title' => 'Tata Usaha',
            'route' => 'administration::dashboard',
            'icon'  => 'bx bxs-buildings',
            'module' => 'digipemad/sia',
            'can' => 'administration::access'
        ]);

        Blade::componentNamespace('Digipemad\\Sia\\' . $this->moduleName . '\\Resources\\Components', $this->moduleNameLower);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/' . $this->moduleNameLower . '.php',
            $this->moduleNameLower
        );
    }

    public function loadDynamicRelationships()
    {

        // User::resolveRelationUsing('student', function (User $user) {
        //     return $user->hasMany(School::class, 'sch_users', 'user_id', 'sch_id');
        // });

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
