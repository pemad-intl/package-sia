<?php

namespace Digipemad\Sia\Administration\Http\Requests\Curricula\Meet;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        // return auth()->user()->can('store', User::class);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'semester_id'   => 'required|exists:acdmc_semesters,id',
            'classroom_id'  => 'nullable|exists:acdmc_classrooms,id',
            'teacher_id'    => 'nullable|exists:empls,id',
            'subject_id'    => 'nullable|exists:acdmc_subjects,id',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'semester_id'   => 'tahun ajaran',
            'classroom_id'  => 'rombel',
            'teacher_id'    => 'pengajar',
            'subject_id'    => 'mapel',
        ];
    }
}