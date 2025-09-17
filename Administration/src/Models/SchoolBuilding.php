<?php

namespace Digipemad\Sia\Administration\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolBuilding extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'sch_buildings';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'kd', 'name','address','village','rt','rw','district_id','postal'
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
     * This hasMany rooms.
     */
    public function rooms () {
        return $this->hasMany(SchoolBuildingRoom::class, 'building_id');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();
        return $this->withTrashed()->where($field, $value)->first();
    }
}
