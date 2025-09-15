<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicSubjectMeetEval extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'acdmc_smt_type_evaluations';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 'smt_id'
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
     * This belongsTo subject.
     */
    public function plan () {
        return $this->belongsTo(AcademicSubjectMeetPlan::class, 'plans_id')->withDefault();
    }

    /**
     * This belongsTo subject.
     */
}