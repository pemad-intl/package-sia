<?php

namespace Digipemad\Sia\Teacher\Http\Requests\Meet;

use Illuminate\Foundation\Http\FormRequest;

class CopyRequest extends FormRequest
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
        $filledMeets = $this->meet->teacher->meets()
                            ->where('subject_id', $this->meet->subject_id)
                            ->where('semester_id', $this->meet->semester_id)
                            ->has('plans')
                            ->get()
                            ->pluck('id')
                            ->toArray();

        return [
            'meet_id'          => 'required|in:'.join(',', ($filledMeets)),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'meet_id'         => 'rombel',
        ];
    }
}