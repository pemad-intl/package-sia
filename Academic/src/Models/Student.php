<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Digipemad\Sia\Boarding\Models\BoardingStudentsLeave;

use Digipemad\Sia\Academic\Models\Traits\StudentTrait;
use Digipemad\Sia\HRMS\Models\EmployeePosition;

class Student extends Model
{
    use SoftDeletes, StudentTrait;

    /**
     * The table associated with the model.
     */
    protected $table = 'stdnts';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id', 'nis', 'nisn', 'nik', 'generation_id', 'entered_at', 'avatar', 'graduated_at', 'graduate_avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'user_id', 'generation_id'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'entered_at', 'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'nis' => 'integer',
        'nisn' => 'integer'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * The relations to eager load on every query.
     */
    public $with = [
        'user'
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
     * Get full name attributes.
     */
    public function getFullNameAttribute () {
        return $this->user->profile->full_name;
    }

    /**
     * This belongsTo user.
     */
    public function user () {
        return $this->belongsTo(\Modules\Account\Models\User::class, 'user_id')->withDefault();
    }

    public function achievementsInSemester($smt_id)
    {
        return $this->hasMany(StudentAchievement::class, 'student_id')
                    ->where('smt_id', $smt_id);
    }

    public function parentBoard(){
        return $this->hasOneThrough(
            Student::class,
            \Digipemad\Sia\Boarding\Models\BoardingStudents::class,
            'student_id',
            'id',
            'id',
            'empl_id'
        );
    }

    public function presenceSummary(array $presences): array
    {
        $studentId = $this->id;

        $summary = [
            'hadir' => 0,
            'sakit' => 0,
            'izin'  => 0,
            'alpha' => 0,
        ];

        foreach ($presences as $presence) {
            if ((int) ($presence['student_id'] ?? 0) !== $studentId) {
                continue;
            }

            switch ((int) $presence['presence']) {
                case 0:
                    $summary['hadir']++;
                    break;
                case 1:
                    $summary['sakit']++;
                    break;
                case 2:
                    $summary['izin']++;
                    break;
                case 3:
                    $summary['alpha']++;
                    break;
            }
        }

        return $summary;
    }


    /**
     * This belongsTo generation.
     */
    public function generation () {
        return $this->belongsTo(Academic::class, 'generation_id')->withDefault();
    }

    /**
     * This hasOne mutation.
     */
    public function mutation () {
        return $this->hasOne(StudentMutation::class, 'student_id')->withDefault();
    }

    public function leaves()
    {
        return $this->hasMany(BoardingStudentsLeave::class, 'student_id');
    }

    /**
     * This hasMany semesters.
     */
    public function semesters () {
        return $this->hasMany(StudentSemester::class, 'student_id');
    }
}
