<?php

namespace Digipemad\Sia\Console;

use Illuminate\Support\ServiceProvider;
use Digipemad\Sia\Console\SiaMigrateCommand;


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
