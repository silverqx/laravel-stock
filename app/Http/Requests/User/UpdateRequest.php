<?php

namespace App\Http\Requests\User;

class UpdateRequest extends CommonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'email'      => 'required|email|max:255|unique:users,email,' . $this->user()->id,
            // Also check config/medialibrary.php max_file_size
            'image'      => 'image|mimes:jpeg,png,gif|max:2048',
            'password'   => [
                'nullable',
                'confirmed',
                'min:9',
                'max:255',
                'regex:/^(?=.*[a-z|A-Z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ]);
    }
}
