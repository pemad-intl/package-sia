<?php

namespace Digipemad\Sia\Administration\Http\Requests\Database\Manage\User\Phone;

use Digipemad\Sia\Administration\Http\Requests\User\Phone\UpdateRequest as FormRequest;

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
            'number'     => 'required|numeric|unique:user_phones,number,'.$this->user->id.',user_id',
            'whatsapp'   => 'boolean'
        ];
    }
}