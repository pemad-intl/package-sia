<?php

namespace Digipemad\Sia\Administration\Http\Requests\Scholar\Semester;

use Illuminate\Foundation\Http\FormRequest;

class PromoteRequest extends FormRequest
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
            'students.*'   => 'nullable|exists:stdnts,id',
            'semester_id' => 'required|exists:acdmc_semesters,id',
            'classroom_id' => 'nullable|exists:acdmc_classrooms,id'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'students.*'   => 'siswa',
            'semester_id' => 'tahun ajaran',
            'classroom_id' => 'rombel'
        ];
    }
}