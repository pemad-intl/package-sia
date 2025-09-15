<?php

namespace Digipemad\Sia\Teacher\Http\Requests\Supervisor;

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
        $students = $this->meet->classroom->stsems->pluck('id');

        return [
            'value.*.ki3_comment' => ['required', 'string', 'max:1000'],          
            'value.*.ki4_evaluation' => ['required', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        
        return [
            'value.*.ki3_comment' => 'Komentar',
            'value.*.ki4_evaluation' => 'Rekomendasi',
        ];
    }
}
