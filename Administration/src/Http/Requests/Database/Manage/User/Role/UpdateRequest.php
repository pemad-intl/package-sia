<?php

namespace Digipemad\Sia\Administration\Http\Requests\Database\Manage\User\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return auth()->user()->can('assign-user-roles', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'roles.*' => 'nullable|exists:roles,id'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'roles.*' => 'hak akses'
        ];
    }
}