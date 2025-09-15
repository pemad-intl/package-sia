<?php

namespace Digipemad\Sia\Administration\Http\Requests\Database\Manage\User;

use Digipemad\Sia\Administration\Http\Requests\Username\UpdateRequest as FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return auth()->user()->can('update', $this->user);
    }
    
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'username'          => 'required|min:4|max:191|regex:/^[a-z\d.]{4,20}$/|unique:users,username,'.$this->user->id,
        ];
    }
}