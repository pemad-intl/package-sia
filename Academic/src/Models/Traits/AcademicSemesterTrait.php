<?php

namespace Digipemad\Sia\Academic\Models\Traits;

trait AcademicSemesterTrait
{
    /**
     * Where opened is true.
     */
    public function scopeOpened($query)
    {
        return $query->where('open', 1);
    }

    /**
     * Where opened is true.
     */
    public function scopeOpenedByDesc($query)
    {
        return $query->opened()->orderByDesc('id');
    }
}