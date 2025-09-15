<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSemesterCounseling extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'stdnt_smt_counselings';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'smt_id', 'category_id', 'description', 'follow_up', 'employee_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'smt_id', 'category_id', 'employee_id'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * This belongsTo semester.
     */
    public function semester () {
        return $this->belongsTo(StudentSemester::class, 'smt_id')->withDefault();
    }

    /**
     * This belongsTo category.
     */
    public function category () {
        return $this->belongsTo(AcademicCounselingCategory::class, 'ctg_id')->withDefault();
    }

    /**
     * This belongsTo employee.
     */
    public function employee () {
        return $this->belongsTo(Employee::class, 'employee_id')->withDefault();
    }
}