<?php

namespace Digipemad\Sia\Teacher;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use App\Services\SidebarManager;
use Illuminate\Support\Facades\Blade;

class TeacherServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Teacher';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'teacher';

    public function boot(SidebarManager $sidebar)
    {
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->loadDynamicRelationships();
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');


        $this->loadViewsFrom(__DIR__ . '/Resources/Views', $this->moduleNameLower);
        $this->loadViewsFrom(__DIR__ . '/Resources/Components', 'x-' . $this->moduleNameLower);

        $sidebar->addMenu([
            'title' => 'Guru',
            'route' => 'teacher::home',
            'icon'  => 'bx bxs-book-content',
            'module' => 'digipemad/sia',
            'can' => 'teacher::access'
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
