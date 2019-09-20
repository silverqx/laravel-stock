<?php

namespace App\Services\Product;

use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

use App\Modules\Product\Product;
use App\Modules\User\User;
use Illuminate\Http\Request;

class ListingService
{
    /**
     * Products query.
     *
     * @var Builder
     */
    private $productsQuery;

    /**
     * Search query.
     *
     * @var string|null
     */
    private $search;

    /**
     * ListingService constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->search = $request->query('search');

        $this->productsQuery = Product::tap(function ($query) {
            if ($this->search)
                $query->where('name', 'like', "%$this->search%");
        });
    }

    /**
     * Get paginated Products for Listing table.
     *
     * @param array  $orderBy   Order by Column name.
     * @param string $direction Order by Direction.
     *
     * @return LengthAwarePaginator
     */
    public function getProducts(array $orderBy, string $direction): LengthAwarePaginator
    {
        return $this->cloneProductsQuery()
            ->select([
                'products.*',
                DB::raw('CONCAT_WS(" ", first_name, last_name) AS user_full_name')
            ])
            ->join('users', 'users.id', '=', 'products.user_id')
            ->tap(function ($query) use ($orderBy, $direction) {
                collect($orderBy)
                    ->each(function ($item) use ($query, $direction) {
                        $query->orderBy($item, $direction);
                    });
            })
            ->paginate(20)
            ->appends('search', $this->search)
            ->appends(compact('orderBy', 'direction'));
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
        return $this->cloneProductsQuery()
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

    /**
     * Clone Products query.
     *
     * @return Builder
     */
    private function cloneProductsQuery(): Builder
    {
        return clone $this->productsQuery;
    }
}
