<?php

namespace Digipemad\Sia\Administration\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolBuildingRoom extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'sch_building_rooms';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'kd', 'name', 'capacity','building_id', 'grade_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'building_id'
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
    protected $casts = [
        'capacity' => 'integer'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * This belongs to building.
     */
    public function building () {
        return $this->belongsTo(SchoolBuilding::class, 'building_id')->withDefault();
    }

    /**
     * This hasMany assets.
     */
    public function assets () {
        return $this->hasMany(SchoolBuildingRoomAsset::class);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();
        return $this->withTrashed()->where($field, $value)->first();
    }
}
