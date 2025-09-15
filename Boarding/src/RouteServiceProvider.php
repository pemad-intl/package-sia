<?php

namespace Digipemad\Sia\Boarding;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Digipemad\Sia\Boarding\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        Route::middleware('web')
            ->namespace($this->moduleNamespace)
            ->name('boarding::')
            ->prefix('boarding')
            ->group(__DIR__ . '/Routes/web.php');

        Route::middleware('api')
            ->namespace($this->moduleNamespace . '\API')
            ->prefix('api/boarding')
            ->name('api::boarding.')
            ->group(__DIR__ . '/Routes/api.php');
    }
}
