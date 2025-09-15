<?php

namespace Digipemad\Sia\Administration\Http\Requests\Database\Manage\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return auth()->user()->can('store', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name'          => 'required|max:191|string',
            'username'          => 'required|min:4|max:191|regex:/^[a-z\d.]{4,20}$/|unique:users,username',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function messages()
    {
        return [
            'username.unique' => 'Isian :attribute sudah digunakan oleh orang lain, silahkan gunakan :attribute lainnya.'
        ];
    }
}