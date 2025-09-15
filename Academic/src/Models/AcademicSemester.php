<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Digipemad\Sia\Academic\Models\Metas\AcademicSemesterMeta as Meta;
use Digipemad\Sia\Academic\Models\Traits\AcademicSemesterTrait;

class AcademicSemester extends Model
{
    use SoftDeletes, AcademicSemesterTrait, Meta;

    /**
     * The table associated with the model.
     */
    protected $table = 'acdmc_semesters';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 'open'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'acdmc_id'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * The relations to eager load on every query.
     */
    public $with = [
        'academic'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'open' => 'boolean'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'full_name'
    ];

    /**
     * Retrieve the model for a bound value.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();
        return $this->withTrashed()->where($field, $value)->first();
    }

    /**
     * Get name attributes.
     */
    public function getFullNameAttribute()
    {
        $academicName = $this->academic->name ?? '';
        return trim($academicName.' '.$this->attributes['name']);
    }


    /**
     * This belongsTo academic.
     */
    public function academic () {
        return $this->belongsTo(Academic::class, 'acdmc_id');
    }

    /**
     * This has many metas.
     */
    public function metas () {
        return $this->hasMany(AcademicSemesterMeta::class, 'semester_id');
    }

    /**
     * This has many classrooms.
     */
    public function classrooms () {
        return $this->hasMany(AcademicClassroom::class, 'semester_id')->orderBy('level_id')->orderBy('name');
    }

    /**
     * This has many stsems.
     */
    public function stsems () {
        return $this->hasMany(StudentSemester::class, 'semester_id');
    }

    /**
     * This has many majors.
     */
    public function majors () {
        return $this->hasMany(AcademicMajor::class, 'semester_id');
    }

    /**
     * This has many superiors.
     */
    public function superiors () {
        return $this->hasMany(AcademicSuperior::class, 'semester_id');
    }

    /**
     * This has many subjects.
     */
    public function subjects () {
        return $this->hasMany(AcademicSubject::class, 'semester_id');
    }
}
