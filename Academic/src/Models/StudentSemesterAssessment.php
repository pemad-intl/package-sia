<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentSemesterAssessment extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'stdnt_smt_asmts';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'smt_id', 'subject_id', 'plan_id', 'type', 'value'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'smt_id', 'subject_id', 'plan_id'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * Enum `type`.
     */
    public static $type = [
        'UH1 - Ulangan Harian 1',
        'UH2 - Ulangan Harian 2',
        'UH3 - Ulangan Harian 3',
        'UH4 - Ulangan Harian 4',
        'PTS - Penilaian Tengah Semester',
        'PAS - Penilaian Akhir Semester',
    ];

    /**
     * Get type attributes.
     */
    public function getTypeNameAttribute () {
        return self::$type[$this->type] ?? null;
    }

    /**
     * This belongsTo semester.
     */
    public function semester () {
        return $this->belongsTo(StudentSemester::class, 'smt_id')->withDefault();
    }

    /**
     * This belongsTo subject.
     */
    public function subject () {
        return $this->belongsTo(AcademicSubject::class, 'subject_id')->withDefault();
    }

    /**
     * This belongsTo plan.
     */
    public function plan () {
        return $this->belongsTo(AcademicSubjectMeetPlan::class, 'plan_id')->withDefault();
    }
}