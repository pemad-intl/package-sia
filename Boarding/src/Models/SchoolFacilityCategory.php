<?php

namespace Digipemad\Sia\Administration\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolFacilityCategory extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'sch_fclt_ctgs';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name'
    ];

    /**
     * This hasMany facitilies.
     */
    public function facitilies () {
        return $this->hasMany(SchoolFacility::class, 'ctg_id');
    }

}