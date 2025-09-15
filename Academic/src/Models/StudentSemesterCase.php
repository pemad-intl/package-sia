<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSemesterCase extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'stdnt_smt_cases';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'smt_id', 'category_id', 'witness', 'description', 'point', 'break_at', 'employee_id'
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
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'point' => 'float',
        'break_at' => 'datetime', 
        'created_at' => 'datetime', 
        'updated_at' => 'datetime'
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
        return $this->belongsTo(AcademicCaseCategory::class, 'category_id')->withDefault();
    }

    /**
     * This belongsTo employee.
     */
    public function employee () {
        return $this->belongsTo(Employee::class, 'employee_id')->withDefault();
    }
}
