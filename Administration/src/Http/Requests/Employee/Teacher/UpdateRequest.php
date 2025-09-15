<?php

namespace Digipemad\Sia\Administration\Http\Requests\Employee\Teacher;

use Modules\Account\Models\UserProfile;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'nip'           => 'required|numeric|unique:empls,nip,'.$this->teacher->employee_id,
            'nuptk'         => 'nullable|numeric|unique:empl_teachers,nuptk,'.$this->teacher->id,
            'entered_at'    => 'nullable|string|date_format:d-m-Y',
            'name'          => 'required|string|max:191',
            'nik'           => 'nullable|numeric',
            'pob'           => 'nullable|string|max:191',
            'dob'           => 'nullable|string|date_format:d-m-Y',
            'sex'           => 'nullable|in:'.join(',', array_keys(UserProfile::$sex)),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'nip'           => 'NIP',
            'nuptk'         => 'NUPTK',
            'name'          => 'nama lengkap siswa',
            'nik'           => 'NIK',
            'pob'           => 'tempat lahir',
            'dob'           => 'tanggal lahir',
            'sex'           => 'jenis kelamin',
            'entered_at'    => 'tanggal masuk',
        ];
    }
}