<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicMajor extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'acdmc_majors';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'semester_id', 'name', 'grade_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'semester_id'
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
     * Retrieve the model for a bound value.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();
        return $this->withTrashed()->where($field, $value)->first();
    }

    /**
     * This belongsTo semester.
     */
    public function semester () {
        return $this->belongsTo(AcademicSemester::class, 'semester_id')->withDefault();
    }

    /**
     * This hasMany classrooms.
     */
    public function classrooms () {
        return $this->hasMany(AcademicClassroom::class, 'major_id');
    }
}
