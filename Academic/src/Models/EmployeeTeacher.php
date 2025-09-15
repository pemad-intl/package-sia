<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Digipemad\Sia\Academic\Models\Traits\EmployeeTeacherTrait;

class EmployeeTeacher extends Model
{
    use SoftDeletes, EmployeeTeacherTrait;

    /**
     * The table associated with the model.
     */
    protected $table = 'empl_teachers';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'employee_id', 'nuptk', 'teaching_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'employee_id'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'teaching_at', 'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [];

    /**
     * The relations to eager load on every query.
     */
    public $with = [
        'employee'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * Retrieve the model for a bound value.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();
        return $this->withTrashed()->where($field, $value)->first();
    }

    /**
     * Get full name attributes.
     */
    public function getFullNameAttribute () {
        return !empty($this->employee->user->profile->full_name) ? $this->employee->user->profile->full_name : $this->employee->user->name;
    }

    /**
     * This belongsTo employee.
     */
    public function employee () {
        return $this->belongsTo(Employee::class, 'employee_id')->withDefault();
    }

    /**
     * This hasOne mutation.
     */
    public function mutation () {
        return $this->hasOne(EmployeeTeacherMutation::class, 'teacher_id')->withDefault();
    }

    /**
     * This hasMany meets.
     */
    public function meets () {
        return $this->hasMany(AcademicSubjectMeet::class, 'teacher_id');
    }

    /**
     * This hasMany plans.
     */
    public function plans () {
        return $this->hasManyThrough(
            AcademicSubjectMeetPlan::class,
            AcademicSubjectMeet::class,
            'teacher_id',
            'meet_id'
        )->selectRaw('acdmc_subject_meet_plans.*, acdmc_subject_meets.semester_id');
    }
}
