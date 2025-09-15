<?php

namespace Digipemad\Sia\Teacher\Http\Requests\Report;

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
            'value.*.ki3_value' => [
                'required',
                'numeric',
                'between:0,100',
                function ($attribute, $value, $fail) use ($students) {
                    $parts = explode('.', $attribute);
                    $id = $parts[1] ?? null;

                    if (!$id || !$students->contains($id)) {
                        $fail('Siswa tidak valid.');
                    }
                },
            ],

            'value.*.ki4_value' => [
                'required',
                'numeric',
                'between:0,100',
                function ($attribute, $value, $fail) use ($students) {
                    $parts = explode('.', $attribute);
                    $id = $parts[1] ?? null;

                    if (!$id || !$students->contains($id)) {
                        $fail('Siswa tidak valid.');
                    }
                },
            ],

            // Jika perlu validasi untuk ki3_predicate, ki3_description, dll, tambahkan di sini
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'value.*.ki3_value' => 'nilai KI3',
            'value.*.ki4_value' => 'nilai KI4',
        ];
    }
}
