<?php

namespace Digipemad\Sia\Administration\Http\Requests\Scholar\Student;

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
            'name'          => 'required|string|max:191',
            'nis'           => 'required|numeric|unique:stdnts,nis,'.$this->student->id,
            'nisn'          => 'nullable|numeric',
            'nik'           => 'nullable|numeric',
            'pob'           => 'nullable|string|max:191',
            'dob'           => 'nullable|string|date_format:d-m-Y',
            'sex'           => 'nullable|in:'.join(',', array_keys(UserProfile::$sex)),
            'hobby_id'      => 'nullable|exists:ref_hobbies,id',
            'desire_id'     => 'nullable|exists:ref_desires,id',
            'entered_at'    => 'nullable|string|date_format:d-m-Y'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'name'          => 'nama lengkap siswa',
            'nis'           => 'NIS',
            'nisn'          => 'NISN',
            'nik'           => 'NIK',
            'pob'           => 'tempat lahir',
            'dob'           => 'tanggal lahir',
            'sex'           => 'jenis kelamin',
            'hobby_id'      => 'hobi',
            'desire_id'     => 'cita-cita',
            'entered_at'    => 'tanggal masuk',
        ];
    }
}