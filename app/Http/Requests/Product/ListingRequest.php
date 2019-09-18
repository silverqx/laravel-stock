<?php

namespace App\Http\Requests\Product;

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
        // TODO add search and check pagination params, like page silver
        return array_merge($this->paginationRules(), []);
    }
}
