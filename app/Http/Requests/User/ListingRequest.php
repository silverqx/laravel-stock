<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

use App\Http\Requests\Concerns\HasSearchableRequest;
use App\Http\Requests\Concerns\HasSortableRequest;

class ListingRequest extends FormRequest
{
    use HasSortableRequest, HasSearchableRequest;

    /**
     * Values for orderBy validation field for a in validator.
     *
     * @var string
     */
    private $orderBy = 'id,first_name,last_name,email';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(
            $this->paginationRules(),
            $this->searchableRules(),
            []
        );
    }
}
