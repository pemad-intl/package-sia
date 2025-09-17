<?php

namespace Digipemad\Sia\Academic\Models\Traits;

use Modules\Account\Models\User;
use Digipemad\Sia\Academic\Models\Student;
use Illuminate\Support\Facades\Hash;

trait StudentTrait
{
    /**
     * Find by nis, .
     */
    public function scopeSearch($query, $search)
    {
        return $query->when($search, function($q, $search) {
            $q->where(function ($subquery) use ($search) {
                $subquery->whereNameLike($search)->orWhere('nis', 'like', '%'.$search.'%');
            });
        });
    }

    /**
     * Find by email.
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
            'name' => $data['name'],
            'username' => $data['nis'],
            'password' => $password,
            'current_team_id' => 1
        ]);

        $user->save();

        $user->profile()->create([
            'name' => $data['name'],
            'nik' => $data['nik'],
            'pob' => $data['pob'],
            'dob' => $data['dob'] ? date('Y-m-d', strtotime($data['dob'])) : null,
            'sex' => $data['sex'],
            'hobby_id' => $data['hobby_id'] ?? null,
            'desire_id' => $data['desire_id'] ?? null
        ]);

        $student = new Student([
            'user_id' => $user->id,
            'nis' => $data['nis'],
            'nisn' => $data['nisn'],
            'generation_id' => $data['acdmc_id'],
            'entered_at' => $data['entered_at'] ? date('Y-m-d', strtotime($data['entered_at'])) : null
        ]);

        $student->save();

        return $student;
    }

    /**
     * Update profile via student.
     */
    public static function updateProfileViaStudent(Student $student, $data)
    {
        $student->user->profile()->update([
            'name' => $data['name'],
            'nik' => $data['nik'],
            'pob' => $data['pob'],
            'dob' => $data['dob'] ? date('Y-m-d', strtotime($data['dob'])) : null,
            'sex' => $data['sex'],
            'hobby_id' => $data['hobby_id'],
            'desire_id' => $data['desire_id']
        ]);

        return $student;
    }

    /**
     * Update student.
     */
    public static function updateStudent(Student $student, $data)
    {
        $student->fill([
            'nis' => $data['nis'],
            'nisn' => $data['nisn'],
            'entered_at' => $data['entered_at'] ? date('Y-m-d', strtotime($data['entered_at'])) : null,
        ]);

        $student->save();

        return $student;
    }

    /**
     * Complete update.
     */
    public static function completeUpdate(Student $student, $data)
    {
        self::updateProfileViaStudent($student, $data);
        self::updateStudent($student, $data);

        return $student;
    }
}