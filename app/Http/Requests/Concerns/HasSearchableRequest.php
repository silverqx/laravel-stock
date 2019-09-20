<?php

namespace App\Http\Requests\Concerns;

trait HasSearchableRequest {
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function searchableRules()
    {
        return [
            'search' => ['nullable', 'min:2', 'max:255', 'string', 'regex:/^[\pL\p{Nd}\,\.\-_\ ]+$/u'],
        ];
    }
}
