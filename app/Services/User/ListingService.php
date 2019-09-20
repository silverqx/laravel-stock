<?php
namespace App\Services\User;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use App\Modules\User\User;

class ListingService
{
    /**
     * Get paginated Users for Listing table.
     *
     * @param string|null $search    Search query.
     * @param array       $orderBy   Order by Column name.
     * @param string      $direction Order by Direction.
     *
     * @return LengthAwarePaginator
     */
    public function getUsers(?string $search, array $orderBy, string $direction): LengthAwarePaginator
    {
        return User
            ::tap(function ($query) use ($search) {
                if ($search)
                    $query->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%");
            })
            ->tap(function ($query) use ($orderBy, $direction) {
                collect($orderBy)
                    ->each(function ($item) use ($query, $direction) {
                        $query->orderBy($item, $direction);
                    });
            })
            ->paginate(20)
            ->appends(compact('search', 'orderBy', 'direction'));
    }
}
