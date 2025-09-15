<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Digipemad\Sia\Academic\Models\Traits\StudentTrait;

class StudentsPackage extends Model
{
    use SoftDeletes, StudentTrait;

    /**
     * The table associated with the model.
     */
    protected $table = 'stdnt_package';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'student_id',
        'name',
        'status'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'entered_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * The relations to eager load on every query.
     */
    public $with = [
        'student'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id')->withDefault();
    }
}
