<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicSubjectSchedule extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'acdmc_subject_schedules';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'subject_id', 'teacher_id', 'assist_id', 'classroom_id', 'day', 'start_at', 'end_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'subject_id', 'teacher_id', 'assist_id', 'classroom_id'
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
     * This belongsTo subject.
     */
    public function subject () {
        return $this->belongsTo(AcademicSubject::class, 'subject_id')->withDefault();
    }

    /**
     * This belongsTo teacher.
     */
    public function teacher () {
        return $this->belongsTo(EmployeeTeacher::class, 'teacher_id')->withDefault();
    }

    /**
     * This belongsTo assist.
     */
    public function assist () {
        return $this->belongsTo(EmployeeTeacher::class, 'assist_id')->withDefault();
    }

    /**
     * This belongsTo classroom.
     */
    public function classroom () {
        return $this->belongsTo(AcademicClassroom::class, 'classroom_id')->withDefault();
    }
}