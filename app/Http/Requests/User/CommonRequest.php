<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CommonRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required', 'min:2', 'max:255', 'string', 'regex:/^[\pL\p{Nd}\.\-_\ ]+$/u'],
            'last_name'  => ['required', 'min:2', 'max:255', 'string', 'regex:/^[\pL\p{Nd}\.\-_\ ]+$/u'],
        ];
    }
}
