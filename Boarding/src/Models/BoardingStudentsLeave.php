<?php

namespace Digipemad\Sia\Boarding\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Illuminate\Support\Facades\DB;
use Modules\Core\Enums\ApprovableResultEnum;
use Digipemad\Sia\Boarding\Models\Traits\Approvable\Approvable;
use Modules\Core\Models\CompanyLeaveCategory;
use Modules\Core\Models\CompanyStudentLeaveCategory;
use Digipemad\Sia\Academic\Models\Student;
use Carbon\Carbon;
use Modules\Docs\Models\Traits\Documentable\Documentable;

class BoardingStudentsLeave extends Model
{
    use Restorable, Searchable, Approvable, Documentable;

    /**
     * The table associated with the model.
     */
    protected $table = 'sch_boarding_stdnts_leaves';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'student_id',
        'ctg_id',
        'dates',
        'description',
        'attachment',
        'created_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'dates' => 'array',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'description',
        'category.name',
        'employee.user.name'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'cancelable_dates'
    ];

    /**
     * Disable selection on approvable.
     */
    public static $approvable_disable_result = [
        ApprovableResultEnum::REVISION
    ];

    /**
     * Disable selection on cancelable.
     */
    public static $cancelable_disable_result = [
        ApprovableResultEnum::REVISION
    ];

    /**
     * Available days to be canceled limitation.
     */
    public static $cancelable_day_limit = 0;

    /**
     * This belongs to employee.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id')->withDefault()->withTrashed();
    }

    public function employee(){
        return $this->belongsTo(\Digipemad\Sia\HRMS\Models\Employee::class, 'empl_id');
    }

    /**
     * This belongs to category.
     */
    public function category()
    {
        return $this->belongsTo(CompanyStudentLeaveCategory::class, 'ctg_id')->withDefault()->withTrashed();
    }

    /**
     * Get cancelable attribute.
     */
    public function getCancelableDatesAttribute()
    {
        return $this->getAttribute('dates')->filter(fn($d) => $d['d'] >= date('Y-m-d', strtotime('+' . self::$cancelable_day_limit . ' days')));
    }

    /**
     * Has cancelable dates.
     */
    public function hasCancelableDates()
    {
        return $this->cancelable_dates->count() > 0;
    }

    /**
     * Scope extract date.
     */
    public function scopeWhereExtractedDatesBetween($query, $start_at, $end_at)
    {
        $table = $this->getTable();

        return $query->whereExists(function ($q) use ($start_at, $end_at, $table) {
            $q->select(DB::raw('1'))
                ->from(DB::raw("jsonb_to_recordset({$table}.dates::jsonb) as x(d date)"))
                ->whereBetween('x.d', [$start_at, $end_at]);
        });
    }

    /**
     * Scope when only pending.
     */
    public function scopeWhenOnlyPending($query, $pending = false)
    {
        return $query->when($pending, fn($s) => $s->whereHas('approvables', fn($approvable) => $approvable->whereResult(ApprovableResultEnum::PENDING)));
    }

    /**
     * Scope when dates between.
     */
    public function scopeWhenDatesBetween($query, $start_at = false, $end_at = false)
    {
        // return $query->where(
        //     fn ($subquery) => $subquery->when($start_at, fn ($q) => $q->where(fn ($s) => $s->whereRaw('dates->>"$[0].d" >= ?', $start_at)->orWhereJsonContains('dates', ['d' => $start_at])))
        //         ->when($end_at, fn ($q) => $q->where(fn ($s) => $s->whereRaw('dates->>"$[0].d" <= ?', $end_at)->orWhereJsonContains('dates', ['d' => $end_at])))
        // );
    }

    /**
     * Scope when created at between.
     */
    public function scopeWhenCreatedAtBetween($query, $start_at = false, $end_at = false, $useOr = false)
    {
        return $query->{$useOr ? 'orWhere' : 'where'}(
            fn($subquery) => $subquery->when($start_at, fn($q) => $q->whereDate($this->table . '.created_at', '>=', $start_at))
                ->when($end_at, fn($q) => $q->whereDate($this->table . '.created_at', '<=', $end_at))
        );
    }

    /**
     * Scope when period.
     */
    public function scopeWhenPeriod($query, $start_at = false, $end_at = false)
    {
        return $query->where(
            fn($subquery) => $subquery->whenDatesBetween($start_at, $end_at)->whenCreatedAtBetween($start_at, $end_at, true)
        );
    }

    /**
     * Scope where approved.
     */
    public function scopeWhereApproved($query)
    {
        return $query->whereHas(
            'approvables',
            fn($a) => $a->where('result', ApprovableResultEnum::APPROVE)
        );
    }

    // /**
    //  * When position of department.
    //  */
    // public function scopeWhenPositionOfDepartment($query, $dep, $pos)
    // {
    //     return $query->when(
    //         $dep,
    //         fn($q1) =>
    //         $q1->whereHas('employee.contract.position.position', fn($q3) => $q3->whereIn('dept_id', (array) $dep))->when(
    //             $pos,
    //             fn($q2) =>
    //             $q2->whereHas('employee.contract.position', fn($q3) => $q3->whereIn('position_id', (array) $pos))
    //         )
    //     );
    // }

    /**
     * Authorization scopes.
     */
    public function can($action)
    {
        return match ($action) {
            'revised' => $this->hasAnyApprovableResultIn('REVISION'),
            'deleted' => !$this->hasApprovables() || ($this->hasApprovables() && !$this->hasAnyApprovableResultIn('REJECT') && ($this->hasAllApprovableResultIn('PENDING') || $this->hasAnyApprovableResultIn('REVISION'))),
            'canceled' => $this->hasApprovables() && $this->approvableTypeIs('approvable') && $this->hasAnyApprovableResultIn('APPROVE') && $this->hasCancelableDates(),
            default => false,
        };
    }
}
