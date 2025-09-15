<?php

namespace Digipemad\Sia\Counseling\Http\Requests\Manage\CaseDescription;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        return [
            'ctg_id'          => 'required|exists:acdmc_case_ctgs,id',
            'name'          => 'required|string|max:191',
            'point'          => 'required|between:0,99.99',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'ctg_id'          => 'kategori',
            'name'          => 'deskripsi',
            'point'          => 'poin',
        ];
    }
}