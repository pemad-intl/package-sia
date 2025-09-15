<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentSemesterReport extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'stdnt_smt_rprts';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'smt_id', 'subject_id', 'ki3_value', 'ki3_predicate', 'ki3_description', 'ki4_value', 'ki4_predicate', 'ki4_description',
        'ki3_comment', 'ki4_evaluation'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'smt_id', 'subject_id'
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
     * This belongsTo semester.
     */
    public function semester () {
        return $this->belongsTo(StudentSemester::class, 'smt_id')->withDefault();
    }

    /**
     * This belongsTo subject.
     */
    public function subject () {
        return $this->belongsTo(AcademicSubject::class, 'subject_id')->withDefault();
    }
}