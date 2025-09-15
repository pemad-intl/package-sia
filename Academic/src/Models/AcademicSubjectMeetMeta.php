<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicSubjectMeetMeta extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'acdmc_subject_meet_metas';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'key', 'content'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'semester_id'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'content' => 'object'
    ];

    /**
     * This belongsTo meet.
     */
    public function meet () {
        return $this->belongsTo(AcademicSubjectMeet::class, 'meet_id');
    }
}