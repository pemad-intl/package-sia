<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAcademicEvaluation extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'stdnt_smt_evaluation';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'smt_id', 'subject_note', 'recommendation_note', 'grade',
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    // protected $hidden = [
    //     'student_id', 'semester_id', 'classroom_id', 'old_id'
    // ];

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
     * This belongsTo student.
     */
    public function semester () {
        return $this->belongsTo(AcademicSemester::class, 'semester_id')->withDefault();
    }
}
