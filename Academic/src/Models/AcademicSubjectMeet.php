<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicSubjectMeet extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'acdmc_subject_meets';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'semester_id', 'subject_id', 'teacher_id', 'classroom_id', 'props'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'semester_id', 'subject_id', 'teacher_id', 'classroom_id'
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
        'semester', 'subject', 'teacher', 'classroom'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'props' => 'object'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * Available colors.
     */
    public static $colors = [
        'danger', 'warning', 'success', 'info', 'primary'
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
     * This belongsTo semester.
     */
    public function semester () {
        return $this->belongsTo(AcademicSemester::class, 'semester_id')->withDefault();
    }
    

    /**
     * This belongsTo subject.
     */
    public function subject () {
        return $this->belongsTo(AcademicSubject::class, 'subject_id')->withDefault();
    }


    /**
     * This belongsTo teacher.
     */
    public function teacher () {
        return $this->belongsTo(Employee::class, 'teacher_id')->withDefault();
    }

    /**
     * This belongsTo classroom.
     */
    public function classroom () {
        return $this->belongsTo(AcademicClassroom::class, 'classroom_id')->withDefault();
    }

    /**
     * This hasMany metas.
     */
    public function metas () {
        return $this->hasMany(AcademicSubjectMeetMeta::class, 'meet_id');
    }

    /**
     * This hasMany plans.
     */
    public function plans () {
        return $this->hasMany(AcademicSubjectMeetPlan::class, 'meet_id');
    }
    /**
     * Scope where semester is opened.
     */
    public function scopeWhereAcsemIn($query, $ids)
    {
        return $query->whereIn('semester_id', (array) $ids);
    }

    /**
     * Default saving when adding new resource.
     */
    public function createAllMetasFromSemesters()
    {
        $vs = [];
        foreach ($this->semester->metas as $meta) {
            $vs[] = [
                'key' => $meta->key,
                'content' => $meta->content
            ];
        }

        $this->metas()->createMany($vs);
        return $this;
    }
}
