<?php

namespace Digipemad\Sia\Administration\Http\Requests\Database\Manage\User\Email;

use Digipemad\Sia\Administration\Http\Requests\User\Email\UpdateRequest as FormRequest;

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
            'email'     => 'required|email|max:191|unique:user_emails,address,'.$this->user->id.',user_id',
        ];
    }
}