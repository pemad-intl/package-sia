<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Digipemad\Sia\Academic\Models\Traits\AcademicSubjectMeetPlanTrait;

class AcademicSubjectMeetPlan extends Model
{
    use SoftDeletes, AcademicSubjectMeetPlanTrait;

    /**
     * The table associated with the model.
     */
    protected $table = 'acdmc_subject_meet_plans';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'meet_id', 'az', 'plan_at', 'hour', 'realized_at', 'comp_id', 'test', 'presence'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'meet_id', 'comp_id'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'plan_at', 'realized_at', 'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * The relations to eager load on every query.
     */
    public $with = [
        'competence', 'meet'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'presence' => 'collection'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * Presence list references.
     */
    public static $presenceList = [
        'Hadir',
        'Sakit',
        'Izin',
        'Alpha'
    ];

    /**
     * This belongsTo meet.
     */
    public function meet () {
        return $this->belongsTo(AcademicSubjectMeet::class, 'meet_id')->withDefault();
    }
    

    /**
     * This belongsTo competence.
     */
    public function competence () {
        return $this->belongsTo(AcademicSubjectCompetence::class, 'comp_id')->withDefault();
    }

    /**
     * This hasMany assessments.
     */
    public function assessments () {
        return $this->hasMany(StudentSemesterAssessment::class, 'plan_id');
    }

    /**
     * Convert presence format.
     */
    public static function transformPresenceFormat($value)
    {
        $presences = [];
        $date = now();

        foreach ($value as $semester_id => $presence) {
            $studentSemester = StudentSemester::find($semester_id);
            
            if (!$studentSemester) continue;
            $type = (int) ($presence['type'] ?? 0);

            $presences[] = [
                'semester_id' => (int) $semester_id,
                'presence'    => $type,
                'name'        => self::$presenceList[$type] ?? 'Tidak Diketahui',
                'at'          => $date,
                'student_id'  =>  $studentSemester->student_id, 
            ];
        }

        return $presences;
    }
}