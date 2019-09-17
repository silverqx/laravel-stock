<?php

namespace App\Http\Requests\Product;

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
            'name'     => ['required', 'min:2', 'max:255', 'string', 'regex:/^[\pL\p{Nd}\,\.\-_\ ]+$/u'],
            'position' => 'required|integer|min:0',
            'balance'  => 'required|integer|min:0',
        ];
    }
}
