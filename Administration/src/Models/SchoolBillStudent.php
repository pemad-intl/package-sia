<?php

namespace Digipemad\Sia\Administration\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Enums\BillCategoryEnum;
use Digipemad\Sia\Academic\Models\StudentSemester;

class SchoolBillStudent extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'sch_bill_students';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'batch_id', 'smt_id', 'meta'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
         'meta' => 'array'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * This belongsTo semester.
     */
    public function semester () {
        return $this->belongsTo(StudentSemester::class, 'smt_id')->withDefault();
    }

    public function getMetaKeysAttribute()
    {
        $meta = $this->meta ?? [];

         if (array_is_list($meta)) {
            return $meta;
        }

        return array_keys($meta);
    }

    public function references()
    {
        return SchoolBillReference::whereIn('id', $this->meta_keys)
            ->whereNull('deleted_at')
            ->get();
    }
}