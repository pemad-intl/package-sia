<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicClassroomPresence extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'acdmc_classroom_presences';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'classroom_id', 'presenced_at', 'presence', 'presenced_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'classroom_id', 'presenced_by'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'presenced_at', 'created_at', 'updated_at'
    ];

    /**
     * The relations to eager load on every query.
     */
    public $with = [
        'classroom', 'presencer'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'presence' => 'collection'
    ];

    /**
     * This belongsTo classroom.
     */
    public function classroom () {
        return $this->belongsTo(AcademicClassroom::class, 'classroom_id')->withDefault();
    }

    /**
     * This belongsTo presencer.
     */
    public function presencer () {
        return $this->belongsTo(Employee::class, 'presenced_by')->withDefault();
    }

}
