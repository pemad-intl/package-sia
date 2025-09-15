<?php

namespace Digipemad\Sia\Administration\Http\Requests\Database\Manage\Role;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return auth()->user()->can('store', Role::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name'          => 'required|min:2|max:191|regex:/^[a-z\d.]{2,20}$/|unique:roles,name',
            'display_name'  => 'required|max:191|string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function messages()
    {
        return [
            'name.unique' => 'Isian :attribute sudah digunakan, silahkan gunakan :attribute lainnya.'
        ];
    }
}