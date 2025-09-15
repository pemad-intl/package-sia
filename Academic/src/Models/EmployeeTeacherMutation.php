<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeTeacherMutation extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'empl_teacher_mutations';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'reason', 'officiated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'teacher_id'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'officiated_at', 'created_at', 'updated_at'
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
     * This belongsTo teacher.
     */
    public function teacher () {
        return $this->belongsTo(EmployeeTeacher::class, 'teacher_id')->withDefault();
    }
}