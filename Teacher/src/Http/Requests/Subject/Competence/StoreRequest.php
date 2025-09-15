<?php

namespace Digipemad\Sia\Teacher\Http\Requests\Subject\Competence;

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
            'kd'            => 'required|string|max:4',
            'name'          => 'required|string|max:191',
            'indicators.*'  => 'required||string|max:191',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'kd'            => 'kode KD',
            'name'          => 'nama',
            'indicators.*'  => 'indikator',
        ];
    }
}