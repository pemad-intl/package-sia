<?php

namespace Digipemad\Sia\Administration\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\HRMS\Models\EmployeeScheduleCategory;
use Modules\HRMS\Models\EmployeeScheduleLesson;
use App\Models\Config;
use Digipemad\Sia\Administration\Models\SchoolBillCycleSemesters;
use Digipemad\Sia\Administration\Models\SchoolBillReference;

class AdministrationBillDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        Model::unguard();

        $smpData = [
            1 => [
                1 => [
                    1 => ['rincian' => 'Pendaftaran', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 200000],
                    2 => ['rincian' => 'Pemakaian kasur', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 250000],
                    3 => ['rincian' => 'Pemakaian ranjang', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 250000],
                    4 => ['rincian' => 'Pemakaian almari & Rak Buku', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 200000],
                    5 => ['rincian' => "Al Quran, Majmu' & Kitab Diba", 'keterangan' => '1x dalam 3 tahun', 'nominal' => 100000],
                    6 => ['rincian' => 'Administrasi keuangan santri', 'keterangan' => '1x setiap tahun', 'nominal' => 100000],
                    7 => ['rincian' => 'Dana UKS', 'keterangan' => '1x setiap tahun', 'nominal' => 200000],
                    8 => ['rincian' => 'Kitab Diniyah', 'keterangan' => '1x setiap tahun', 'nominal' => 150000],
                    9 => ['rincian' => 'Khotmil Quran', 'keterangan' => '(Menyesuaikan kebijakan yayasan)', 'nominal' => 0],
                ],
                2 => [
                    1 => ['rincian' => 'MPLS & Ziarah Santri Baru', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 350000],
                    2 => ['rincian' => '5 Stell Seragam (OSIS, Pramuka, Gamis, Batik Yayasan & Olahraga)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 1150000],
                    3 => ['rincian' => 'Tes Potensi Akademik Siswa', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 250000],
                    4 => ['rincian' => 'Pengadaan Buku Pegangan siswa', 'keterangan' => '1x setiap tahun', 'nominal' => 600000],
                    5 => ['rincian' => 'ITT Sekolah & Diniyah 1 Tahun', 'keterangan' => '1x setiap tahun', 'nominal' => 2400000],
                    6 => ['rincian' => 'Biaya penyelenggaraan Ujian', 'keterangan' => '1x setiap tahun', 'nominal' => 100000],
                    7 => ['rincian' => 'Dana Kegiatan Siswa', 'keterangan' => '1x setiap tahun', 'nominal' => 350000],
                ],
                3 => [
                    1 => ['rincian' => 'Silver (Pilihan 1)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 1500000],
                    2 => ['rincian' => 'Gold (Pilihan 2)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 2500000],
                    3 => ['rincian' => 'Platinum (Pilihan 3)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 3500000],
                ],
                4 => [
                    1 => ['rincian' => 'Biaya Makan 3x dan air minum', 'keterangan' => '1x setiap bulan', 'nominal' => 360000],
                    2 => ['rincian' => 'Syahriah pondok', 'keterangan' => '1x setiap bulan', 'nominal' => 190000],
                ],
            ],
            2 => [
                1 => [
                    1 => ['rincian' => 'Pendaftaran', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 250000],
                    2 => ['rincian' => 'Pemakaian kasur', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 300000],
                    3 => ['rincian' => 'Pemakaian ranjang', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 280000],
                    4 => ['rincian' => 'Pemakaian almari & Rak Buku', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 210000],
                    5 => ['rincian' => "Al Quran, Majmu' & Kitab Diba", 'keterangan' => '1x dalam 3 tahun', 'nominal' => 120000],
                    6 => ['rincian' => 'Administrasi keuangan santri', 'keterangan' => '1x setiap tahun', 'nominal' => 120000],
                    7 => ['rincian' => 'Dana UKS', 'keterangan' => '1x setiap tahun', 'nominal' => 220000],
                    8 => ['rincian' => 'Kitab Diniyah', 'keterangan' => '1x setiap tahun', 'nominal' => 170000],
                    9 => ['rincian' => 'Khotmil Quran', 'keterangan' => '(Menyesuaikan kebijakan yayasan)', 'nominal' => 0],
                ],
                2 => [
                    1 => ['rincian' => 'MPLS & Ziarah Santri Baru', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 370000],
                    2 => ['rincian' => '5 Stell Seragam', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 1200000],
                    3 => ['rincian' => 'Tes Potensi Akademik Siswa', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 270000],
                    4 => ['rincian' => 'Pengadaan Buku Pegangan siswa', 'keterangan' => '1x setiap tahun', 'nominal' => 650000],
                    5 => ['rincian' => 'ITT Sekolah & Diniyah 1 Tahun', 'keterangan' => '1x setiap tahun', 'nominal' => 2450000],
                    6 => ['rincian' => 'Biaya penyelenggaraan Ujian', 'keterangan' => '1x setiap tahun', 'nominal' => 120000],
                    7 => ['rincian' => 'Dana Kegiatan Siswa', 'keterangan' => '1x setiap tahun', 'nominal' => 370000],
                ],
                3 => [
                    1 => ['rincian' => 'Silver (Pilihan 1)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 1600000],
                    2 => ['rincian' => 'Gold (Pilihan 2)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 2700000],
                    3 => ['rincian' => 'Platinum (Pilihan 3)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 3700000],
                ],
                4 => [
                    1 => ['rincian' => 'Biaya Makan 3x dan air minum', 'keterangan' => '1x setiap bulan', 'nominal' => 380000],
                    2 => ['rincian' => 'Syahriah pondok', 'keterangan' => '1x setiap bulan', 'nominal' => 200000],
                ],
            ],
            3 => [
                1 => [
                    1 => ['rincian' => 'Pendaftaran', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 275000],
                    2 => ['rincian' => 'Pemakaian kasur', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 330000],
                    3 => ['rincian' => 'Pemakaian ranjang', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 308000],
                    4 => ['rincian' => 'Pemakaian almari & Rak Buku', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 231000],
                    5 => ['rincian' => "Al Quran, Majmu' & Kitab Diba", 'keterangan' => '1x dalam 3 tahun', 'nominal' => 132000],
                    6 => ['rincian' => 'Administrasi keuangan santri', 'keterangan' => '1x setiap tahun', 'nominal' => 132000],
                    7 => ['rincian' => 'Dana UKS', 'keterangan' => '1x setiap tahun', 'nominal' => 242000],
                    8 => ['rincian' => 'Kitab Diniyah', 'keterangan' => '1x setiap tahun', 'nominal' => 187000],
                    9 => ['rincian' => 'Khotmil Quran', 'keterangan' => '(Menyesuaikan kebijakan yayasan)', 'nominal' => 0],
                ],
                2 => [
                    1 => ['rincian' => 'MPLS & Ziarah Santri Baru', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 407000],
                    2 => ['rincian' => '5 Stell Seragam', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 1320000],
                    3 => ['rincian' => 'Tes Potensi Akademik Siswa', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 297000],
                    4 => ['rincian' => 'Pengadaan Buku Pegangan siswa', 'keterangan' => '1x setiap tahun', 'nominal' => 715000],
                    5 => ['rincian' => 'ITT Sekolah & Diniyah 1 Tahun', 'keterangan' => '1x setiap tahun', 'nominal' => 2695000],
                    6 => ['rincian' => 'Biaya penyelenggaraan Ujian', 'keterangan' => '1x setiap tahun', 'nominal' => 132000],
                    7 => ['rincian' => 'Dana Kegiatan Siswa', 'keterangan' => '1x setiap tahun', 'nominal' => 407000],
                ],
                3 => [
                    1 => ['rincian' => 'Silver (Pilihan 1)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 1760000],
                    2 => ['rincian' => 'Gold (Pilihan 2)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 2970000],
                    3 => ['rincian' => 'Platinum (Pilihan 3)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 4070000],
                ],
                4 => [
                    1 => ['rincian' => 'Biaya Makan 3x dan air minum', 'keterangan' => '1x setiap bulan', 'nominal' => 418000],
                    2 => ['rincian' => 'Syahriah pondok', 'keterangan' => '1x setiap bulan', 'nominal' => 220000],
                ],
            ]
        ];

        $smaData = [
            // ================= GEL. 1 =================
            1 => [
                1 => [
                    1 => ['rincian' => 'Pendaftaran', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 200000],
                    2 => ['rincian' => 'Pemakaian kasur', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 250000],
                    3 => ['rincian' => 'Pemakaian ranjang', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 250000],
                    4 => ['rincian' => 'Pemakaian almari & Rak Buku', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 200000],
                    5 => ['rincian' => "Al Quran, Majmu' & Kitab Diba", 'keterangan' => '1x dalam 3 tahun', 'nominal' => 100000],
                    6 => ['rincian' => 'Administrasi keuangan santri', 'keterangan' => '1x setiap tahun', 'nominal' => 100000],
                    7 => ['rincian' => 'Dana UKS', 'keterangan' => '1x setiap tahun', 'nominal' => 200000],
                    8 => ['rincian' => 'Kitab Diniyah', 'keterangan' => '1x setiap tahun', 'nominal' => 150000],
                    9 => ['rincian' => 'Khotmil Quran', 'keterangan' => '(Menyesuaikan kebijakan yayasan)', 'nominal' => 0],
                ],
                2 => [
                    1 => ['rincian' => 'MPLS & Ziarah Santri Baru', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 350000],
                    2 => ['rincian' => '5 Stell Seragam (OSIS, Pramuka, Gamis, Batik Yayasan & Olahraga)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 1150000],
                    3 => ['rincian' => 'Tes Potensi Akademik Siswa', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 250000],
                    4 => ['rincian' => 'Pengadaan Buku Pegangan siswa', 'keterangan' => '1x setiap tahun', 'nominal' => 750000],
                    5 => ['rincian' => 'ITT Sekolah & Diniyah 1 Tahun', 'keterangan' => '1x setiap tahun', 'nominal' => 2400000],
                    6 => ['rincian' => 'Biaya penyelenggaraan Ujian', 'keterangan' => '1x setiap tahun', 'nominal' => 100000],
                    7 => ['rincian' => 'Dana Kegiatan Siswa', 'keterangan' => '1x setiap tahun', 'nominal' => 350000],
                ],
                3 => [
                    1 => ['rincian' => 'Silver (Pilihan 1)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 1500000],
                    2 => ['rincian' => 'Gold (Pilihan 2)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 2500000],
                    3 => ['rincian' => 'Platinum (Pilihan 3)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 3500000],
                ],
                4 => [
                    1 => ['rincian' => 'Biaya Makan 3x dan air minum', 'keterangan' => '1x setiap bulan', 'nominal' => 360000],
                    2 => ['rincian' => 'Syahriah pondok', 'keterangan' => '1x setiap bulan', 'nominal' => 190000],
                ],
            ],

            // ================= GEL. 2 =================
            2 => [
                1 => [
                    1 => ['rincian' => 'Pendaftaran', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 300000],
                    2 => ['rincian' => 'Pemakaian kasur', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 320000],
                    3 => ['rincian' => 'Pemakaian ranjang', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 290000],
                    4 => ['rincian' => 'Pemakaian almari & Rak Buku', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 220000],
                    5 => ['rincian' => "Al Quran, Majmu' & Kitab Diba", 'keterangan' => '1x dalam 3 tahun', 'nominal' => 150000],
                    6 => ['rincian' => 'Administrasi keuangan santri', 'keterangan' => '1x setiap tahun', 'nominal' => 130000],
                    7 => ['rincian' => 'Dana UKS', 'keterangan' => '1x setiap tahun', 'nominal' => 230000],
                    8 => ['rincian' => 'Kitab Diniyah', 'keterangan' => '1x setiap tahun', 'nominal' => 180000],
                    9 => ['rincian' => 'Khotmil Quran', 'keterangan' => '(Menyesuaikan kebijakan yayasan)', 'nominal' => 0],
                ],
                2 => [
                    1 => ['rincian' => 'MPLS & Ziarah Santri Baru', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 400000],
                    2 => ['rincian' => '5 Stell Seragam (Lengkap)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 1200000],
                    3 => ['rincian' => 'Tes Potensi Akademik Siswa', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 300000],
                    4 => ['rincian' => 'Pengadaan Buku Pegangan siswa', 'keterangan' => '1x setiap tahun', 'nominal' => 800000],
                    5 => ['rincian' => 'ITT Sekolah & Diniyah 1 Tahun', 'keterangan' => '1x setiap tahun', 'nominal' => 2500000],
                    6 => ['rincian' => 'Biaya penyelenggaraan Ujian', 'keterangan' => '1x setiap tahun', 'nominal' => 150000],
                    7 => ['rincian' => 'Dana Kegiatan Siswa', 'keterangan' => '1x setiap tahun', 'nominal' => 400000],
                ],
                3 => [
                    1 => ['rincian' => 'Silver (Pilihan 1)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 1600000],
                    2 => ['rincian' => 'Gold (Pilihan 2)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 2600000],
                    3 => ['rincian' => 'Platinum (Pilihan 3)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 3600000],
                ],
                4 => [
                    1 => ['rincian' => 'Biaya Makan 3x dan air minum', 'keterangan' => '1x setiap bulan', 'nominal' => 370000],
                    2 => ['rincian' => 'Syahriah pondok', 'keterangan' => '1x setiap bulan', 'nominal' => 200000],
                ],
            ],

            // ================= GEL. 3 =================
            3 => [
                1 => [
                    1 => ['rincian' => 'Pendaftaran', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 350000],
                    2 => ['rincian' => 'Pemakaian kasur', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 350000],
                    3 => ['rincian' => 'Pemakaian ranjang', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 320000],
                    4 => ['rincian' => 'Pemakaian almari & Rak Buku', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 250000],
                    5 => ['rincian' => "Al Quran, Majmu' & Kitab Diba", 'keterangan' => '1x dalam 3 tahun', 'nominal' => 180000],
                    6 => ['rincian' => 'Administrasi keuangan santri', 'keterangan' => '1x setiap tahun', 'nominal' => 150000],
                    7 => ['rincian' => 'Dana UKS', 'keterangan' => '1x setiap tahun', 'nominal' => 250000],
                    8 => ['rincian' => 'Kitab Diniyah', 'keterangan' => '1x setiap tahun', 'nominal' => 200000],
                    9 => ['rincian' => 'Khotmil Quran', 'keterangan' => '(Menyesuaikan kebijakan yayasan)', 'nominal' => 0],
                ],
                2 => [
                    1 => ['rincian' => 'MPLS & Ziarah Santri Baru', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 450000],
                    2 => ['rincian' => '5 Stell Seragam (Lengkap)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 1250000],
                    3 => ['rincian' => 'Tes Potensi Akademik Siswa', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 350000],
                    4 => ['rincian' => 'Pengadaan Buku Pegangan siswa', 'keterangan' => '1x setiap tahun', 'nominal' => 850000],
                    5 => ['rincian' => 'ITT Sekolah & Diniyah 1 Tahun', 'keterangan' => '1x setiap tahun', 'nominal' => 2600000],
                    6 => ['rincian' => 'Biaya penyelenggaraan Ujian', 'keterangan' => '1x setiap tahun', 'nominal' => 200000],
                    7 => ['rincian' => 'Dana Kegiatan Siswa', 'keterangan' => '1x setiap tahun', 'nominal' => 450000],
                ],
                3 => [
                    1 => ['rincian' => 'Silver (Pilihan 1)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 1700000],
                    2 => ['rincian' => 'Gold (Pilihan 2)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 2700000],
                    3 => ['rincian' => 'Platinum (Pilihan 3)', 'keterangan' => '1x dalam 3 tahun', 'nominal' => 3700000],
                ],
                4 => [
                    1 => ['rincian' => 'Biaya Makan 3x dan air minum', 'keterangan' => '1x setiap bulan', 'nominal' => 380000],
                    2 => ['rincian' => 'Syahriah pondok', 'keterangan' => '1x setiap bulan', 'nominal' => 210000],
                ],
            ],
        ];

        $this->loopEducation($smpData, 4);
        $this->loopEducation($smaData, 5);
    }

     private function mapCycle(string $keterangan): int
    {
        if (str_contains($keterangan, 'bulan')) {
            return 3; 
        } elseif (str_contains($keterangan, 'tahun')) {
            return 2; 
        } elseif (str_contains($keterangan, '3 tahun')) {
            return 1; 
        }
        return 0; 
    }

    private function loopEducation($data, $flag){
        foreach ($data as $batchNumber => $categories) {
            $batch = SchoolBillCycleSemesters::create([
                'grade_id' => $flag,
                'name' => "Gelombang {$batchNumber}",
                'semester_id' => 75,
            ]);

            foreach ($categories as $categoryId => $items) {
                foreach ($items as $index => $item) {
                    $uniqueId = uniqid();
                    SchoolBillReference::create([
                        'batch_id'         => $batch->id,
                        'kd'               => "B{$batchNumber}-C{$categoryId}-{$uniqueId}",
                        'name'             => $item['rincian'],
                        'type'             => 1, 
                        'type_class'       => $flag, 
                        'payment_category' => $categoryId,
                        'payment_cycle'    => $this->mapCycle($item['keterangan']),
                        'price'            => $item['nominal'],
                    ]);
                }
            }
        }

        if ($flag === 4) {
            echo "✅ Data SMP selesai diproses.\n";
        } elseif ($flag === 5) {
            echo "✅ Data SMA selesai diproses.\n";
        }
    }
}
