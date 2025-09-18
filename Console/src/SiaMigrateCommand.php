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

        // Tentukan urutan modul
        $moduleOrder = [
            'Academic',
            'Administration',
            'Boarding',
            'Counseling',
            'Teacher'
        ];

        // Buat array baru dengan modul sesuai urutan
        $orderedModules = [];
        foreach ($moduleOrder as $modName) {
            foreach ($modules as $modulePath) {
                if (str_contains($modulePath, $modName)) {
                    $orderedModules[] = $modulePath;
                }
            }
        }

        foreach ($orderedModules as $modulePath) {
            $migrationsPath = $modulePath . '/src/Database/Migrations';
            if (File::exists($migrationsPath)) {
                $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $migrationsPath);
                $relativePath = str_replace('\\', '/', $relativePath);
                
                $this->info("Migrating: $modulePath");
                $this->call('migrate', [
                    '--path' => $relativePath,
                    '--database' => 'pgsql', 
                    '--force' => $this->option('force')
                ]);
            }
        }

        $this->info('Semua modul SIA berhasil dimigrasi.');
    }
}
