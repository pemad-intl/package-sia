<?php

namespace Digipemad\Sia\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\References\Grade;

use Digipemad\Sia\Academic\Models\Traits\EmployeeTrait;

class Employee extends Model
{
    use SoftDeletes, EmployeeTrait;

    /**
     * The table associated with the model.
     */
    protected $table = 'empls';

    /**
     * The attributes that are mass assignable.
     */
    //generation_id
    protected $fillable = [
        'user_id', 'nip', 'group', 'sk_number', 'entered_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    // protected $hidden = [
    //     'user_id', 'generation_id'
    // ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'entered_at', 'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [];

    /**
     * The relations to eager load on every query.
     */
    public $with = [
        'user'
    ];

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

    /**
     * This hasOne teacher.
     */
    public function teacher () {
        return $this->hasOne(EmployeeTeacher::class, 'employee_id');
    }

    /**
     * This belongsTo generation.
     */
    // public function generation () {
    //     return $this->belongsTo(Academic::class, 'generation_id')->withDefault();
    // }
}
