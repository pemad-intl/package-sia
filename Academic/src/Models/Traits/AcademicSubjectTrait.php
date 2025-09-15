<?php

namespace Digipemad\Sia\Academic\Models\Traits;

use Digipemad\Sia\Academic\Models\EmployeeTeacher;
use Digipemad\Sia\Academic\Models\AcademicSemester;
use Digipemad\Sia\HRMS\Models\Employee;

trait AcademicSubjectTrait
{
    /**
     * Find by nip, .
     */
    public function scopeInTeacherAndSemester($query, Employee $teacher, AcademicSemester $acsem)
    {
        return $query->whereIn('id', $teacher->meets->pluck('subject_id')->toArray())
                     ->where('semester_id', $acsem->id);
    }
}