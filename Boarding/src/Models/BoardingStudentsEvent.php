<?php

namespace Digipemad\Sia\Boarding\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Digipemad\Sia\Academic\Models\Student;
use Digipemad\Sia\Boarding\Models\BoardingReferenceEvent;
use Digipemad\Sia\Administration\Models\SchoolBuilding;
use Modules\HRMS\Models\Employee;

class BoardingStudentsEvent extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'sch_boarding_student_event';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'modelable_type',
        'modelable_id',
        'student_id',
        'event_id',
        'teacher_id', 
        'supervisor_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function event(){
        return $this->belongsTo(BoardingReferenceEvent::class, 'event_id');
    }

    public function ground()
    {
        return $this->belongsTo(SchoolBuilding::class, 'building_id');
    }

    public function teacher(){
        return $this->belongsTo(Employee::class, 'teacher_id');
    }

    public function supervisor(){
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();
        return $this->withTrashed()->where($field, $value)->first();
    }

    public function modelable()
    {
        return $this->morphTo();
    }
}
