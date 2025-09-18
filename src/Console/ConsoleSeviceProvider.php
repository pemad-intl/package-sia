<?php

namespace Digipemad\Sia\Console;

use Illuminate\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            SiaMigrateCommand::class,
        ]);
    }

    public function boot()
    {
        //
    }
}
