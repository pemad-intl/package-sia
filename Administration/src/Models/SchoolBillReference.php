<?php

namespace Digipemad\Sia\Administration\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Enums\BillCategoryEnum;
use Modules\Core\Enums\StudentEducationEnum;
use Modules\Core\Enums\BillReferencesCategoryEnum;
use Modules\Core\Enums\PaymentCycleEnum;

class SchoolBillReference extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'sch_bill_references';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'batch_id', 'kd', 'name', 'type', 'type_class', 'payment_category', 'payment_cycle', 'price'
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
        'price' => 'integer',
        'type' => BillCategoryEnum::class,
        'type_class' => StudentEducationEnum::class,
        'payment_category' => BillReferencesCategoryEnum::class,
        'payment_cycle' => PaymentCycleEnum::class
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    public function batch(){
        return $this->belongsTo(SchoolBillCycleSemesters::class, 'batch_id');
    }
}