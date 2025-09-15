<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Digipemad\Sia\Academic\Models\Traits\AcademicSubjectTrait;

class AcademicSubject extends Model
{
    use SoftDeletes, AcademicSubjectTrait;

    /**
     * The table associated with the model.
     */
    protected $table = 'acdmc_subjects';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'kd', 'name', 'semester_id', 'level_id', 'category_id', 'color_id', 'score_standard'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'level_id', 'semester_id', 'category_id'
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
        'level', 'category'
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
     * Retrieve the model for a bound value.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $field = $field ?? $this->getRouteKeyName();
        return $this->withTrashed()->where($field, $value)->first();
    }

    /**
     * This belongsTo semester.
     */
    public function semester () {
        return $this->belongsTo(AcademicSemester::class, 'semester_id')->withDefault();
    }

    /**
     * This belongsTo level.
     */
    public function level () {
        return $this->belongsTo(\App\Models\References\GradeLevel::class, 'level_id')->withDefault();
    }

    /**
     * This belongsTo category.
     */
    public function category () {
        return $this->belongsTo(AcademicSubjectCategory::class, 'category_id')->withDefault();
    }

    /**
     * This hasMany schedules.
     */
    public function schedules () {
        return $this->hasMany(AcademicSubjectSchedule::class, 'subject_id');
    }

    /**
     * This hasMany competences.
     */
    public function competences () {
        return $this->hasMany(AcademicSubjectCompetence::class, 'subject_id');
    }

    /**
     * This hasMany meets.
     */
    public function meets () {
        return $this->hasMany(AcademicSubjectMeet::class, 'subject_id');
    }
}
