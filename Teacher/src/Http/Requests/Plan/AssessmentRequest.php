<?php

namespace Digipemad\Sia\Teacher\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;
use Digipemad\Sia\Academic\Models\StudentSemesterAssessment;
use Digipemad\Sia\Academic\Models\AcademicSubjectMeetEval;


class AssessmentRequest extends FormRequest
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

        $types = AcademicSubjectMeetEval::pluck('id')->toArray();
        $students = auth()->user()->teacher->plans()->findOrFail($this->plan->id)->meet->classroom->students->pluck('pivot.id');

        return [
            'type'   => 'required|in:'.join(',', $types),
            'value.*'   => [
                'required',
                'between:0,100',
                function ($attribute, $value, $fail) use ($students) {
                    if (!$students->contains(str_replace('value.', '', $attribute))) {
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
            'type'      => 'jenis penilaian',
            'value.*'   => 'nilai'
        ];
    }
}