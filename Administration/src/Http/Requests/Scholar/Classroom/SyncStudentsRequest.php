<?php

namespace Digipemad\Sia\Administration\Http\Requests\Scholar\Classroom;

use Illuminate\Foundation\Http\FormRequest;

class SyncStudentsRequest extends FormRequest
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
            'stsems.*'   => 'nullable|exists:stdnt_smts,id',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'stsems.*'   => 'siswa',
        ];
    }
}