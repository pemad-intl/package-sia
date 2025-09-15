<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HRMS\Models\Employee;

class AcademicClassroom extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'acdmc_classrooms';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'semester_id', 'level_id', 'name', 'room_id', 'major_id', 'superior_id','supervisor_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'semester_id', 'level_id', 'room_id', 'major_id', 'superior_id','supervisor_id'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * The relations to eager load on every query.
     */
    public $with = [
        'level', 'room', 'major', 'superior'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'full_name',
        'major_superior',
    ];

    /**
     * Retrieve the model for a bound value.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();
        return $this->withTrashed()->where($field, $value)->first();
    }

    /**
     * Get major superior attributes.
     */
    public function getMajorSuperiorAttribute () {
        return trim(join(' ', [$this->major->name, $this->superior->name]));
    }

    /**
     * Get full name attributes.
     */
    public function getFullNameAttribute () {
        return join(' - ', array_filter([$this->name, $this->major_superior]));
    }

    /**
     * This belongsTo semester.
     */
    public function semester () {
        return $this->belongsTo(AcademicSemester::class, 'semester_id')->withDefault();
    }

    /**
     * This belongsTo level.
     */
    public function level () {
        return $this->belongsTo(\App\Models\References\GradeLevel::class, 'level_id')->withDefault();
    }

    /**
     * This belongsTo room.
     */
    public function room () {
        return $this->belongsTo(\Digipemad\Sia\Administration\Models\SchoolBuildingRoom::class, 'room_id')->withDefault();
    }

    /**
     * This belongsTo major.
     */
    public function major () {
        return $this->belongsTo(AcademicMajor::class, 'major_id')->withDefault();
    }

    /**
     * This belongsTo superior.
     */
    public function superior () {
        return $this->belongsTo(AcademicSuperior::class, 'superior_id')->withDefault();
    }

    /**
     * This belongsTo supervisor.
     */
    public function supervisor () {
        return $this->belongsTo(\Digipemad\Sia\HRMS\Models\Employee::class, 'supervisor_id')->withDefault();
    }

    /**
     * This hasMany studentSemesters.
     */
    public function stsems () {
        return $this->hasMany(StudentSemester::class, 'classroom_id');
    }

    /**
     * This hasMany meets.
     */
    public function meets () {
        return $this->hasMany(AcademicSubjectMeet::class, 'classroom_id');
    }

    /**
     * This hasMany presences.
     */
    public function presences () {
        return $this->hasMany(AcademicClassroomPresence::class, 'classroom_id');
    }

    /**
     * This belongsToMany students.
     */
    public function students () {
        return $this->belongsToMany(Student::class, 'stdnt_smts', 'classroom_id', 'student_id')->withPivot('id');
    }
}
