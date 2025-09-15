<?php

namespace Digipemad\Sia\Teacher\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;
use Digipemad\Sia\Academic\Models\AcademicSubjectMeetPlan;

class PresenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        $presenceList = array_keys(AcademicSubjectMeetPlan::$presenceList);
        $students = auth()->user()->teacher->plans()->findOrFail($this->plan->id)->meet->classroom->students->pluck('pivot.id');

        return [
            'presence.*'   => [
                'required',
                'in:'.join(',', $presenceList),
                function ($attribute, $value, $fail) use ($students) {
                    if (!$students->contains(str_replace('presence.', '', $attribute))) {
                        return $fail('Siswa tidak valid.');
                    }
                },
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'presence.*'   => 'presensi',
        ];
    }
}