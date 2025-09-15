<?php

namespace Digipemad\Sia\Academic\Models\Traits;

use Modules\Account\Models\User;
use Digipemad\Sia\Academic\Models\Employee;

trait EmployeeTrait
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
        return $query->whereHas('user.profile', function ($profile) use ($name) {
            $profile->where('name', 'like', '%'.$name.'%');
        });
    }

    /**
     * Complete insert.
     */
    public static function completeInsert($data, $password)
    {
        $user = new User([
            'username' => $data['nip'],
            'password' => bcrypt($password)
        ]);

        $user->save();

        $user->profile()->create([
            'name' => $data['name'],
            'nik' => $data['nik'],
            'pob' => $data['pob'],
            'dob' => isset($data['dob']) ? date('Y-m-d', strtotime($data['dob'])) : null,
            'sex' => isset($data['sex']) ? $data['sex'] : null
        ]);

        $employee = new Employee([
            'user_id' => $user->id,
            'nip' => $data['nip'],
            'generation_id' => $data['acdmc_id'],
            'entered_at' => isset($data['joinded_at']) ? date('Y-m-d', strtotime($data['joined_at'])) : null,
        ]);


        $employee->save();

        return $employee;
    }

    /**
     * Insert from user.
     */
    public static function insertFromUser(User $user, $data)
    {
        $employee = new Employee([
            'user_id' => $user->id,
            'nip' => $data['nip'],
      //      'generation_id' => $data['acdmc_id'],
            'entered_at' => isset($data['entered_at']) ? date('Y-m-d', strtotime($data['entered_at'])) : null,
        ]);

        $employee->save();

        return $employee;
    }
}
