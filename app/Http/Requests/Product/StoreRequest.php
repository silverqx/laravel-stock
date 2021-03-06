<?php

namespace App\Http\Requests\Product;

class StoreRequest extends CommonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            // Also check config/medialibrary.php max_file_size
            'image'    => 'required|image|mimes:jpeg,png,gif|max:2048',
        ]);
    }
}
