<?php

namespace Digipemad\Sia\Boarding\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Digipemad\Sia\Academic\Models\Student;
use Digipemad\Sia\Administration\Models\SchoolBuilding;
use Digipemad\Sia\Administration\Models\SchoolBuildingRoom;

class BoardingStudents extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'sch_boarding_student_buildings';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'student_id',
        'building_id',
        'empl_id',
        'room_id',
        'grade_id'
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

    public function student(){
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function ground(){
        return $this->belongsTo(SchoolBuilding::class, 'building_id');
    }

    public function employee()
    {
        return $this->belongsTo(\Modules\HRMS\Models\Employee::class, 'empl_id');
    }

    public function room(){
        return $this->belongsTo(SchoolBuildingRoom::class, 'room_id');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();
        return $this->withTrashed()->where($field, $value)->first();
    }
}
