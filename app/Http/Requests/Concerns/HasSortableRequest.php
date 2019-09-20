<?php

namespace App\Http\Requests\Concerns;

trait HasSortableRequest {
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function paginationRules()
    {
        return [
            // is array to support multi sort
            'orderBy'   => "required_with:direction|array|in:$this->orderBy",
            'direction' => 'string|in:asc,desc',
        ];
    }
}
