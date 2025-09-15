<?php

namespace Digipemad\Sia\Administration\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Digipemad\Sia\Academic\Models\AcademicSemester;

class SchoolBillCycleSemesters extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'sch_bill_batchs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'semester_id', 'grade_id', 'name'
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

    
    public function references(){
       return $this->hasMany(SchoolBillReference::class, 'batch_id');
    }

    public function semesters(){
        return $this->belongsTo(AcademicSemester::class, 'semester_id');
    }
}