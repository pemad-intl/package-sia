<?php

namespace Digipemad\Sia\Teacher\Http\Requests\Meet;

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
        $comps = $this->meet->subject->competences->pluck('id')->toArray();
        $plans = $this->meet->plans->pluck('id');

        return [
            'plans.*.plan_at'   => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($plans) {
                    if (!$plans->contains(explode('.', $attribute)[1])) {
                        return $fail('Rencana tidak valid.');
                    }
                },
            ],
            'plans.*.hour'      => [
                'required',
                'numeric',
                'min:1',
                'max:4',
                function ($attribute, $value, $fail) use ($plans) {
                    if (!$plans->contains(explode('.', $attribute)[1])) {
                        return $fail('Rencana tidak valid.');
                    }
                },
            ],
            'plans.*.test'      => [
                'boolean',
                function ($attribute, $value, $fail) use ($plans) {
                    if (!$plans->contains(explode('.', $attribute)[1])) {
                        return $fail('Rencana tidak valid.');
                    }
                },
            ],
            'plans.*.comp_id'   => [
                'nullable',
                'in:'.join(',', $comps),
                function ($attribute, $value, $fail) use ($plans) {
                    if (!$plans->contains(explode('.', $attribute)[1])) {
                        return $fail('Rencana tidak valid.');
                    }
                },
            ]
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'plans.*.plan_at'   => 'rencana',
            'plans.*.hour'      => 'jam pelajaran',
            'plans.*.test'      => 'ulangan',
            'plans.*.comp_id'   => 'kompetensi'
        ];
    }
}