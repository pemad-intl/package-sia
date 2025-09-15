<?php

namespace Digipemad\Sia\Boarding\Models;

use Modules\Core\Enums\ApprovableResultEnum;
use App\Models\Traits\Restorable\Restorable;
use Illuminate\Database\Eloquent\Model;

class BoardingCompanyApprovable extends Model
{
    use Restorable;

    /**
     * The table associated with the model.
     */
    protected $table = 'sch_boarding_approvable';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'modelable_type', 'modelable_id', 'userable_type', 'userable_id', 'level', 'result', 'reason', 'history'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'history' => 'object',
        'result' => ApprovableResultEnum::class,
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'type'
    ];

    /**
     * Get type attribute.
     */
    public function getTypeAttribute()
    {
        return $this->cancelable == 0 ? 'pengajuan' : 'pembatalan';
    }

    /**
     * Get the parent modelable model (employee vacations, etc.).
     */
    public function modelable()
    {
        return $this->morphTo();
    }

    /**
     * Get the parent userable model (employee vacations, etc.).
     */
    public function userable()
    {
        return $this->morphTo();
    }

    /**
     * Scope where max level result in.
     */
    public function scopeWhereMaxLevelResultIs($query, ApprovableResultEnum $enum)
    {
        // Only available with "with" methods
        return $query->where('level', $query->max('level'))->whereResult($enum);
    }
}
