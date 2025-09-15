<?php

namespace Digipemad\Sia\Administration\Http\Requests\Employee\Teacher;

use Modules\Account\Models\UserProfile;
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
        $rules = [
            'acdmc_id'      => 'required|exists:acdmcs,id',
            'nip'           => 'required|numeric|unique:empls,nip',
            'nuptk'         => 'nullable|numeric|unique:empl_teachers,nuptk',
            'entered_at'    => 'nullable|string|date_format:d-m-Y'
        ];

        if ($this->user == 1) {
            $rules['user_id'] = 'required|exists:users,id|unique:empls,user_id';
        } else {
            $rules['name'] = 'required|string|max:191';
            $rules['nik'] = 'nullable|numeric';
            $rules['pob'] = 'nullable|string|max:191';
            $rules['dob'] = 'nullable|string|date_format:d-m-Y';
            $rules['sex'] = 'nullable|in:'.join(',', array_keys(UserProfile::$sex));
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'acdmc_id'      => 'tahun ajaran',
            'nip'           => 'NIP',
            'nuptk'         => 'NUPTK',
            'user_id'       => 'pengguna',
            'name'          => 'nama lengkap siswa',
            'nik'           => 'NIK',
            'pob'           => 'tempat lahir',
            'dob'           => 'tanggal lahir',
            'sex'           => 'jenis kelamin',
            'entered_at'    => 'tanggal masuk',
        ];
    }
}