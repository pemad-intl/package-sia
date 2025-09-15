<?php

namespace Digipemad\Sia\Administration\Http\Requests\Database\Curricula;

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
            'kd'          => 'required|max:191|string|unique:sch_curriculas,kd,'.$this->curricula->id,
            'name'          => 'required|max:191|string',
            'year'          => 'required|date_format:Y',
        ];
    }
}