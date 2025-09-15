<?php

namespace Digipemad\Sia\Administration\Http\Requests\Database\Manage\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return auth()->user()->can('update', $this->role);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name'      => 'required|string|max:191|unique:roles,name,'.$this->role->id,
            'display_name' => 'nullable|string|max:191',
            'permissions.*' => 'nullable|exists:permissions,id'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'name' => 'kode peran',
            'display_name' => 'nama peran',
            'permissions.*' => 'hak akses'
        ];
    }
}