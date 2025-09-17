<?php

namespace Digipemad\Sia\Academic\Models\Traits\Account;

use Modules\HRMS\Models\Employee;
trait UserTrait
{
    /**
     * This hasMany student
     */
    public function student () {
        return $this->hasOne(\Digipemad\Sia\Academic\Models\Student::class, 'user_id');
    }

    /**
     * Is user is student
     */
    public function isStudent()
    {
        return $this->student()->exists();
    }

    /**
     * This hasOne employee
     */
    public function employee () {
        return $this->hasOne(Employee::class, 'user_id');
    }

    /**
     * Is user is employee
     */
    public function isEmployee()
    {
        return $this->employee()->exists();
    }

    /**
     * This hasMany teacher
     */
    public function teacher () {
        return $this->hasOneThrough(
            \Digipemad\Sia\Academic\Models\EmployeeTeacher::class,
            \Digipemad\Sia\Academic\Models\Employee::class,
            'user_id',
            'employee_id'
        );
    }

    /**
     * Is user is teacher
     */
    public function isTeacher()
    {
        return $this->teacher()->exists();
    }

    /**
     * Is user is counselor
     */
    public function isCounselor()
    {
        return $this->hasPermissions(['manage-cases', 'counsel-students']);
    }
}
