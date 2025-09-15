<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Academic extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'acdmcs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 'year'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [];

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
     * This hasMany calendars.
     */
    public function calendars () {
        return $this->hasMany(AcademicCalendar::class, 'acdmc_id');
    }

    /**
     * This hasMany semesters.
     */
    public function semesters () {
        return $this->hasMany(AcademicSemester::class, 'acdmc_id');
    }

    /**
     * This hasMany students.
     */
    public function students () {
        return $this->hasMany(Student::class, 'acdmc_id');
    }
}
