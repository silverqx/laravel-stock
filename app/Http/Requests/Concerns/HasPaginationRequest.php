<?php

namespace App\Http\Requests\Concerns;

trait HasPaginationRequest {
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function paginationRules()
    {
        return [
            // array to support multi sort
            'orderBy'   => 'required_with:direction|array|in:id,name,balance,position',
            'direction' => 'string|in:asc,desc',
        ];
    }
}
