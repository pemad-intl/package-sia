<?php

namespace Digipemad\Sia\Academic\Models\Traits;

trait AcademicSubjectMeetPlanTrait
{
    /**
     * Scope where semester is opened.
     */
    public function scopeWhereAcsemIn($query, $ids)
    {
        return $query->whereHas('meet', function ($meet) use ($ids) {
            return $meet->whereIn('semester_id', (array) $ids);
        });
    }
    
    /**
     * Get closest plans.
     */
    public function scopeGetClosestPlans($query, string $range = '+1 week')
    {
        return $query->whereDate('plan_at', '>=', now())
                     ->whereDate('plan_at', '<=', date('Y-m-d', strtotime($range)))
                     ->orderBy('plan_at')
                     ->get();
    }

    /**
     * Get unpresenced plans.
     */
    public function scopeGetUnpresencedPlans($query)
    {
        return $query->whereDate('plan_at', '<', now())
                     ->whereNull('presence')
                     ->orderBy('plan_at')
                     ->get();
    }
}