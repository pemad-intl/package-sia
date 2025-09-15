<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicSubjectCompetence extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'acdmc_subject_comps';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'subject_id', 'kd', 'name', 'indicators', 'employee_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'subject_id', 'employee_id'
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
    protected $casts = [
        'indicators' => 'object'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'full_name'
    ];

    /**
     * Get full name attributes.
     */
    public function getFullNameAttribute () {
        return trim(join(' ', array_filter([$this->kd, $this->name]))) ?: null;
    }

    /**
     * This belongsTo subject.
     */
    public function subject () {
        return $this->belongsTo(AcademicSubject::class, 'subject_id')->withDefault();
    }

    /**
     * This belongsTo employee.
     */
    public function employee () {
        return $this->belongsTo(Employee::class, 'employee_id')->withDefault();
    }
}