<?php

namespace Digipemad\Sia\Boarding\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Digipemad\Sia\Academic\Models\Student;
use Digipemad\Sia\Administration\Models\SchoolBuilding;
use Digipemad\Sia\Boarding\Enums\BoardingEventTypeEnum;

class BoardingReferenceEvent extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'sch_boarding_event';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'type',
        'start_date',
        'end_date',
        'in',
        'out',
        'type_participant'
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
    protected $casts = [
        'type' => BoardingEventTypeEnum::class,
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();
        return $this->withTrashed()->where($field, $value)->first();
    }
}
