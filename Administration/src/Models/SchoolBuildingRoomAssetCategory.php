<?php

namespace Digipemad\Sia\Administration\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolBuildingRoomAssetCategory extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'sch_building_room_asset_ctgs';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name'
    ];

    /**
     * This hasMany assets.
     */
    public function assets () {
        return $this->hasMany(SchoolBuildingRoomAsset::class, 'ctg_id');
    }

}