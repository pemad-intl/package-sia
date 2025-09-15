<?php

namespace Digipemad\Sia\Administration\Http\Requests\Curricula\Subject;

use App\Models\References\GradeLevel;
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
        $levels = GradeLevel::where('grade_id', config('school.grade'))->get();

        return [
            'kd'            => 'required|max:191|string',
            'name'          => 'required|max:191|string',
            'semester_id'   => 'required|exists:acdmc_semesters,id',
            'level_id'      => 'required|in:'.join(',', $levels->pluck('id')->toArray()),
            'category_id'   => 'nullable|exists:acdmc_subject_ctgs,id',
            'score_standard' => 'required'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'kd'            => 'kode mapel',
            'name'          => 'nama mapel',
            'semester_id'   => 'tahun ajaran',
            'level_id'      => 'kelas',
            'category_id'   => 'kategori',
            'score_standard' => 'nilai'
        ];
    }
}