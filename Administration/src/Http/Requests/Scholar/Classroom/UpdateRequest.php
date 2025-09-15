<?php

namespace Digipemad\Sia\Administration\Http\Requests\Scholar\Classroom;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'level_id'      => 'required|exists:ref_grade_levels,id',
            'name'          => 'required|max:191|string',
            'room_id'       => 'nullable|exists:sch_building_rooms,id',
            'major_id'      => 'nullable|exists:acdmc_majors,id',
            'superior_id'   => 'nullable|exists:acdmc_superiors,id',
            'supervisor_id' => 'nullable|exists:empls,id',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'level_id'      => 'jenjang kelas',
            'name'          => 'nama rombel',
            'room_id'       => 'ruang',
            'major_id'      => 'jurusan',
            'superior_id'   => 'unggulan',
            'supervisor_id' => 'wali kelas',
        ];
    }
}
