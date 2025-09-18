<?php

namespace Digipemad\Sia\Academic;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Blade;
use Modules\Account\Models\User;
use Digipemad\Sia\Academic\Models\Student;
use App\Services\SidebarManager;
use Digipemad\Sia\Academic\Models\Traits\Account\UserTrait as UserAcademicTrait;
use Illuminate\Database\Eloquent\Builder;

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

    public function register()
    {        
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/' . $this->moduleNameLower . '.php',
            'modules.' . $this->moduleNameLower
        );

    }

    public function boot(SidebarManager $sidebar)
    {
        $this->loadDynamicRelationships();
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);

        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');


        $this->loadViewsFrom(__DIR__ . '/Resources/Views', $this->moduleNameLower);
        $this->loadViewsFrom(__DIR__ . '/Resources/Components', 'x-' . $this->moduleNameLower);

        Blade::componentNamespace('Digipemad\\Sia\\' . $this->moduleName . '\\Resources\\Components', $this->moduleNameLower);
    }

    public function loadDynamicRelationships()
    {
        User::resolveRelationUsing('student', function (User $user) {
            return $user->hasOne(Student::class, 'user_id');
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
}
