<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicCalendar extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'acdmc_calendars';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'date', 'description', 'category_id', 'holiday'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'acmdc_id', 'category_id'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'date', 'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'holiday' => 'boolean'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * This belongs to academic.
     */
    public function academic () {
        return $this->belongsTo(Academic::class, 'acdmc_id')->withDefault();
    }

    /**
     * This belongs to category.
     */
    public function category () {
        return $this->belongsTo(AcademicCalendarCategory::class, 'category_id')->withDefault();
    }
}