<?php

namespace Digipemad\Sia\Academic\Models\Traits;

use Modules\Account\Models\User;
use Digipemad\Sia\Academic\Models\EmployeeTeacher;
use Digipemad\Sia\Academic\Models\Traits\EmployeeTrait;;
use Digipemad\Sia\HRMS\Models\Employee;

trait EmployeeTeacherTrait
{
    /**
     * Find by nip, .
     */
    public function scopeSearch($query, $search)
    {
        return $query->when($search, function($q, $search) {
            $q->where(function ($subquery) use ($search) {
                $subquery->whereNameLike($search)->orWhere('nip', 'like', '%'.$search.'%');
            });
        });
    }

    /**
     * Find by name.
     */
    public function scopeWhereNameLike($query, $name)
    {
        return $query->whereHas('employee.user.profile', function ($profile) use ($name) {
            $profile->where('name', 'like', '%'.$name.'%');
        });
    }

    /**
     * Complete insert.
     */
    public static function completeInsert($data, $password)
    {
        $teacher = new EmployeeTeacher([
            'nuptk' => $data['nuptk'] ?? null
        ]);

        // $employee = EmployeeTrait::completeInsert($data, $password);
        // $employee->teacher()->save($teacher);

        return $teacher;
    }

    /**
     * Insert from user.
     */
    public static function insertFromUser($data)
    {
        $teacher = new EmployeeTeacher([
            'employee_id' => $data['employee_id'],
            'nuptk' => $data['nuptk'] ?? null
        ]);

        $teacher->save();

        // $employee = Employee::insertFromUser($user, $data);
        // $employee->teacher()->save($teacher);

        return $teacher;
    }

    /**
     * Update profile via teacher.
     */
    public static function updateProfileViaTeacher(Employee $teacher, $data)
    {
        // dd($teacher->user);
//        dd($data);
        $teacher->user->profile()->update([
            // 'name' => $data['name'],
            'nik' => $data['nik'],
            'pob' => $data['pob'],
            'dob' => $data['dob'] ? date('Y-m-d', strtotime($data['dob'])) : null,
            'sex' => $data['sex']
        ]);

        return $teacher;
    }

    /**
     * Update teacher.
     */
    public static function updateTeacher(Employee $teacher, $data)
    {
        $teacher->employee()->update([
            'nip' => $data['nip'],
        ]);

        $teacher->fill([
            'nuptk' => $data['nuptk'],
        ]);

        $teacher->save();

        return $teacher;
    }

    /**
     * Complete update.
     */
    public static function completeUpdate(Employee $teacher, $data)
    {
        // dd('ok');
        self::updateProfileViaTeacher($teacher, $data);
        self::updateTeacher($teacher, $data);

        return $teacher;
    }
}
