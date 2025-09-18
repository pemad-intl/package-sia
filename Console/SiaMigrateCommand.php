<?php

namespace Digipemad\Sia\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SiaMigrateCommand extends Command
{
    protected $signature = 'sia:migrate {--force : Jalankan migrasi di production}';
    protected $description = 'Jalankan migrasi untuk semua modul SIA';

    public function handle()
    {
        $vendorPath = base_path('vendor/digipemad/sia');
        $modules = File::directories($vendorPath); 

        foreach ($modules as $modulePath) {
            $migrationsPath = $modulePath . '/src/Database/Migrations';
            if (File::exists($migrationsPath)) {
                $this->info("Migrating: $modulePath");
                $this->call('migrate', [
                    '--path' => str_replace(base_path().'/', '', $migrationsPath),
                    '--force' => $this->option('force')
                ]);
            }
        }

        $this->info('Semua modul SIA berhasil dimigrasi.');
    }
}
