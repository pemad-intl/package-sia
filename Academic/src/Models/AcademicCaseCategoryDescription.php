<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicCaseCategoryDescription extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'acdmc_case_ctg_descs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'ctg_id', 'name', 'point'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'ctg_id'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'point' => 'float'
    ];
    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * This belongs to category.
     */
    public function category () {
        return $this->belongsTo(AcademicCaseCategory::class, 'ctg_id');
    }
}