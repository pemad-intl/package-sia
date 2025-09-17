<?php

namespace Digipemad\Sia\Academic\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use Digipemad\Academic\Models\Academic;
use Digipemad\Academic\Models\AcademicSemester;
use Digipemad\Academic\Models\AcademicSubjectCategory;
use Digipemad\Academic\Models\AcademicSubject;

class AcademicDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $grades = [4, 5];

        $acdmcs = [];
        $smts = [];
        foreach ($grades as $grade) {
            for ($i = 1988; $i <= date('Y'); $i++) {
                $acdmcId = count($acdmcs) + 1;

                $acdmcs[] = [
                    'name' => (string) ($i.'/'.($i + 1)),
                    'year' => $i
                ];

                $smts[] = [
                    'acdmc_id' => $acdmcId,
                    'name' => 'GASAL',
                    'open' => 0
                ];

                $smts[] = [
                    'acdmc_id' => $acdmcId,
                    'name' => 'GENAP',
                    'open' => 0
                ];
            }
        }

        Academic::insert($acdmcs);

        foreach ($smts as $v) {
          $smt = new AcademicSemester($v);
          $smt->save();
          $smt->createAllMetas();
        }

        $currentYear = date('Y');
        $currentAcademic = Academic::where('year', $currentYear)->first();

        if ($currentAcademic) {
            AcademicSemester::where('acdmc_id', $currentAcademic->id)
                ->where('name', 'GASAL')
                ->update(['open' => 't']);
        }

        $subjectCategory = [
            4 => ['general_lesson' => [
                7 => [
                    'Matematika',
                    'Bahasa indonesia',
                    'Bahasa inggris',
                ],
                8 => [
                    'Sejarah',
                    'PKN',
                ],
                9 => [
                    'Pendidikan Jasmani dan Kesehatan'
                ]
            ],
            'spesific_lesson' => [
                7 => [
                    'IPS'
                ],
                8 => [
                    'IPA',
                    'Ekonomi',
                ],
                9 => [
                    'Akuntansi'
                ]
            ]],
            5 => ['general_lesson' => [
                10 => [
                    'Matematika',
                    'Bahasa indonesia',
                    'Bahasa inggris',
                ],
                11 => [
                    'Sejarah',
                    'PKN',
                ],
                12 => [
                    'Pendidikan Jasmani dan Kesehatan'
                ]
            ],
            'spesific_lesson' => [
                10 => [
                    'IPS'
                ],
                11 => [
                    'IPA',
                    'Ekonomi',
                ],
                12 => [
                    'Akuntansi'
                ]
            ]]
        ];

        $activeSemester = AcademicSemester::where('open', 't')->first();

        foreach ($subjectCategory as $gradeId => $types) {
            foreach ($types as $type => $levels) {
                $categories = ($type === 'general_lesson' ? 'Mapel Umum' : 'Mapel Penjurusan');

                $category = AcademicSubjectCategory::create([
                    'name' => $categories,
                ]);

                // Loop tiap level
                foreach ($levels as $levelId => $subjects) {
                    foreach ($subjects as $subjectName) {
                        AcademicSubject::create([
                            'kd' => rand(10000, 99999),
                            'name' => $subjectName,
                            'semester_id' => $activeSemester?->id ?? null,
                            'level_id' => $levelId,
                            'category_id' => $category->id,
                            'score_standard' => 80
                        ]);
                    }
                }
            }
        }
    }
}
