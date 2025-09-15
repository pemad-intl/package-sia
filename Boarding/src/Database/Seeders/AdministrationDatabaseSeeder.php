<?php

namespace Digipemad\Sia\Administration\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\HRMS\Models\EmployeeScheduleCategory;
use Modules\HRMS\Models\EmployeeScheduleLesson;
use App\Models\Config;
use Digipemad\Sia\Administration\Models\SchoolBuilding;

class AdministrationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Config::insert([
            [
                'kd' => 'short_name',
                'title' => 'Nama pendek madrasah',
                'val' => 'MASPA'
            ],
            [
                'kd' => 'name',
                'title' => 'Nama madrasah',
                'val' => 'MA Sunan Pandanaran'
            ],
            [
                'kd' => 'long_name',
                'title' => 'Nama panjang madrasah',
                'val' => 'Madrasah Aliyah Sunan Pandanaran'
            ],
        ]);

        SchoolBuilding::insert([
            [
                'kd' => 'PI',
                'name' => 'PUTRI',
                'address' => 'JL. KALIURANG KM 12.5, CANDI',
                'village' => 'SARDONOHARJO',
                'district_id' => 3404120,
                'postal' => 55581
            ],
            [
                'kd' => 'PA',
                'name' => 'PUTRA',
                'address' => 'JL. KALIURANG KM 12.5, CANDI',
                'village' => 'SARDONOHARJO',
                'district_id' => 3404120,
                'postal' => 55581
            ],
        ]);
    }
}
