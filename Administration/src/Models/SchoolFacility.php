<?php

namespace Digipemad\Sia\Administration\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolFacility extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'sch_fclts';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'kd', 'name', 'year', 'ctg_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'ctg_id'
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
     * This belongs to category.
     */
    public function category () {
        return $this->belongsTo(SchoolFacilityCategory::class, 'ctg_id')->withDefault();
    }

    /**
     * This belongsToMany operators.
     */
    public function operators ()
    {
        return $this->belongsToMany(\Modules\Account\Models\User::class, 'sch_fclt_ops', 'fclt_id', 'user_id');
    }
}