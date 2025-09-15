<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicCalendarCategory extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'acdmc_calendars';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'acmdc_id'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * This hasMany calendars.
     */
    public function calendars () {
        return $this->hasMany(AcademicCalendar::class, 'category_id');
    }
}