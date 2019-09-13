<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\Concerns\HasPaginationRequest;

class ListingRequest extends FormRequest
{
    use HasPaginationRequest;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge($this->paginationRules(), []);
    }
}
