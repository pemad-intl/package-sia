<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAchievement extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'stdnt_smt_achievements';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'smt_id', 'name', 'date', 'student_id', 'classroom_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    // protected $hidden = [
    //     'student_id', 'semester_id', 'classroom_id', 'old_id'
    // ];

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
     * This belongsTo student.
     */
    public function student () {
        return $this->belongsTo(Student::class, 'student_id')->withDefault();
    }

    /**
     * This belongsTo semester.
     */
    public function semester () {
        return $this->belongsTo(StudentSemester::class, 'smt_id')->withDefault();
    }

    /**
     * This belongsTo classroom.
     */
    public function classroom () {
        return $this->belongsTo(AcademicClassroom::class, 'classroom_id')->withDefault();
    }

    /**
     * This hasMany assessments.
     */
    public function assessments () {
        return $this->hasMany(StudentSemesterAssessment::class, 'smt_id');
    }

    /**
     * This hasMany cases.
     */
    public function cases () {
        return $this->hasMany(StudentSemesterCase::class, 'smt_id');
    }

    /**
     * This hasMany counselings.
     */
    public function counselings () {
        return $this->hasMany(StudentSemesterCounseling::class, 'smt_id');
    }

    /**
     * This hasMany reports.
     */
    public function reports () {
        return $this->hasMany(StudentSemesterReport::class, 'smt_id');
    }
}
