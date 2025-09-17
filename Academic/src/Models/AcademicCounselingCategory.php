<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicCounselingCategory extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'acdmc_counseling_ctgs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'deleted_at', 'created_at', 'updated_at'
    ];
}