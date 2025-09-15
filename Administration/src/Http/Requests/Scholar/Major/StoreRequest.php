<?php

namespace Digipemad\Sia\Administration\Http\Requests\Scholar\Major;

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
            'name'          => 'required|max:191|string',
        ];
    }
}