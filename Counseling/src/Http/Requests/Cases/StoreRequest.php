<?php

namespace Digipemad\Sia\Counseling\Http\Requests\Cases;

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
            'smt_id.*'        => 'required|exists:stdnt_smts,id',
            'category_id'   => 'required|exists:acdmc_case_ctgs,id',
            'description'   => 'required|string|max:191',
            'point'         => 'nullable|regex:/^\d*(\.\d{2})?$/',
            'witness'       => 'required|string|max:191',
            'break_at'      => 'required|date_format:Y-m-d\TH:i'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'smt_id'        => 'siswa',
            'category_id'   => 'kategori',
            'description'   => 'deskripsi',
            'point'         => 'poin',
            'witness'       => 'saksi',
            'break_at'      => 'tanggal dan waktu'
        ];
    }
}