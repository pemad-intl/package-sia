<?php

namespace Digipemad\Sia\Teacher\Http\Requests\Meet;

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
            'meets'          => 'required|numeric|min:1|max:50',
            'count'          => 'required|numeric|min:1|max:4',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'meets'         => 'jumlah pertemuan',
            'count'         => 'jam per pertemuan',
        ];
    }
}