<?php

namespace Digipemad\Sia\Academic\Models\Metas;

use Digipemad\Sia\Academic\Models\AcademicSemesterMeta as BaseModel;

trait AcademicSemesterMeta
{
    protected $meta_keys = [
        'asmts.uh' => [
            'title' => 'Nilai Ulangan Uarian',
            'value' => 40
        ],
        'asmts.uts' => [
            'title' => 'Nilai Penilaian Tengah Semester',
            'value' => 25
        ],
        'asmts.uas' => [
            'title' => 'Nilai Penilaian Akhir Semester',
            'value' => 25
        ],
        'asmts.presence' => [
            'title' => 'Kehadiran',
            'value' => 10,
            'type' => 0
        ],
    ];

    protected $asmts_presence_types = [
        'Jumlah kehadiran siswa dibagi jumlah kehadiran guru',
        'Jumlah kehadiran siswa dibagi jumlah pertemuan (seharusnya)'
    ];

    /**
     * Default saving when adding new resource.
     */
    public function createAllMetas()
    {
        $vs = [];
        foreach ($this->meta_keys as $key => $value) {
            $vs[] = [
                'key' => $key,
                'content' => $value
            ];
        }

        $this->metas()->createMany($vs);
        return $this;
    }

    /**
     * Get all meta keys.
     */
    public function getAllMetaKeys ()
    {
        return $this->meta_keys;
    }

    /**
     * Get `$asmts_presence_types` value.
     */
    public function getAsmtsPresenceTypes ($i)
    {
        switch ($i) {
            case 0: // Jumlah kehadiran siswa dibagi jumlah kehadiran guru
                break;
            case 1: // Jumlah kehadiran siswa dibagi jumlah pertemuan (seharusnya)
                break;            
            default:break;
        }
    }
}