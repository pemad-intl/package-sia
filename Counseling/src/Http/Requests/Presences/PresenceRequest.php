<?php

namespace Digipemad\Sia\Counseling\Http\Requests\Presences;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Digipemad\Sia\Academic\Models\AcademicSubjectMeetPlan;

class PresenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $presenceTypes = array_keys(AcademicSubjectMeetPlan::$presenceList);

        return [
            'classroom_id' => 'required|exists:acdmc_classrooms,id',
            'presenced_at' => 'required|date',

            'presence' => 'required|array',
            'presence.*.type' => [
                'required',
                Rule::in($presenceTypes),
            ],
            'presence.*.student_id' => [
                'required',
                'exists:stdnts,id',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'classroom_id' => 'rombel',
            'presenced_at' => 'waktu presensi',
            'presence.*.type' => 'jenis presensi',
            'presence.*.student_id' => 'siswa',
        ];
    }
}
