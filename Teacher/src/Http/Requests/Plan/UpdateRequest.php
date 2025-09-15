<?php

namespace Digipemad\Sia\Teacher\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
        $comps = $this->plan->meet->subject->competences->pluck('id')->toArray();

        return [
            'plan_at'   => 'required|date',
            'hour'      => 'required|numeric|min:1|max:4',
            'test'      => 'boolean',
            'comp_id'   => 'required_if:test,0|in:'.join(',', $comps)
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'plan_at'   => 'rencana',
            'hour'      => 'jam pelajaran',
            'test'      => 'ulangan',
            'comp_id'   => 'kompetensi'
        ];
    }
}