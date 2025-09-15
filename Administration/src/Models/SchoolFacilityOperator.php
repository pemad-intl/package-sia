<?php

namespace Digipemad\Sia\Administration\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolFacilityOperator extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'sch_fclt_ops';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id', 'as'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'fclt_id', 'user_id'
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
     * This belongs to facility.
     */
    public function facility () {
        return $this->belongsTo(SchoolFacility::class, 'fclt_id')->withDefault();
    }

    /**
     * This belongs to user.
     */
    public function user () {
        return $this->belongsTo(\Modules\Account\Models\User::class, 'user_id')->withDefault();
    }
}