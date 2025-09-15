<?php

namespace Digipemad\Sia\Counseling\Http\Requests\Counseling;

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
        return [
            'category_id'   => 'required|exists:acdmc_case_ctgs,id',
            'description'   => 'required|string|max:191',
            'follow_up'     => 'required|string|max:191',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'category_id'   => 'kategori',
            'description'   => 'deskripsi',
            'follow_up'     => 'tindak lanjut',
        ];
    }
}