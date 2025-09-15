<?php

namespace Digipemad\Sia\Administration\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolBuildingRoomAsset extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'sch_building_room_assets';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'room_id','name', 'ctg_id', 'count', 'condition'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'room_id', 'ctg_id','id'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'created_at', 'updated_at'
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
     * This belongs to room.
     */
    public function room () {
        return $this->belongsTo(SchoolBuildingRoom::class, 'room_id')->withDefault();
    }

    /**
     * This belongs to category.
     */
    public function category () {
        return $this->belongsTo(SchoolBuildingRoomAssetCategory::class, 'ctg_id')->withDefault();
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();
        return $this->withTrashed()->where($field, $value)->first();
    }
}
