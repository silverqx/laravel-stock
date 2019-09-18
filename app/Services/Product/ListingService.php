<?php

namespace App\Services\Product;

use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Modules\Product\Product;
use App\Modules\User\User;

class ListingService
{
    /**
     * Products query.
     *
     * @var Builder
     */
    private $productsQuery;

    /**
     * Get paginated Products for Listing table.
     *
     * @param string|null $search    Search query.
     * @param array       $orderBy   Order by Column name.
     * @param string      $direction Order by Direction.
     *
     * @return LengthAwarePaginator Paginated Products.
     */
    public function getProducts(?string $search, array $orderBy, string $direction): LengthAwarePaginator
    {
        $this->productsQuery = Product::where('name', 'like', "%$search%")
            ->tap(function ($query) use ($orderBy, $direction) {
                collect($orderBy)
                    ->each(function ($item) use ($query, $direction) {
                        $query->orderBy($item, $direction);
                    });
            });

        return $this->productsQuery
            ->paginate(20)
            ->appends(compact('search', 'orderBy', 'direction'));
    }

    /**
     * Get User's products count.
     *
     * @param int $userId User ID.
     *
     * @return int Products count.
     */
    public function userProductsCount(int $userId): int
    {
        return $this->productsQuery
            ->where('user_id', $userId)
            ->count();
    }

    /**
     * Should Hide Actions Column?
     *
     * @param User $user              Logged User.
     * @param int  $userProductsCount User's Product Count.
     *
     * @return bool
     * @throws \Exception
     */
    public function hideActions(User $user, int $userProductsCount): bool
    {
        return !$user->hasAnyPermission(['edit any product', 'delete any product'])
            && (
                (
                    ($user->can('edit own product') || $user->can('delete own product'))
                        && $userProductsCount === 0
                )
                || ($user->cant('edit own product') && $user->cant('delete own product'))
            );
    }
}
