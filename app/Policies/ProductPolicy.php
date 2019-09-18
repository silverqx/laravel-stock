<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Modules\Product\Product;
use App\Modules\User\User;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any products.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('viewAny product');
    }

    /**
     * Determine whether the user can view the product.
     *
     * @param User    $user
     * @param Product $product
     *
     * @return mixed
     */
    public function view(User $user, Product $product)
    {
        return $user->can('view product');
    }

    /**
     * Determine whether the user can create products.
     *
     * @param User  $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create product');
    }

    /**
     * Determine whether the user can update the product.
     *
     * @param User    $user
     * @param Product $product
     *
     * @return mixed
     */
    public function update(User $user, Product $product)
    {
        if ($user->can('edit any product'))
            return true;

        if ($user->can('edit own product'))
            return $user->id === $product->user_id;
    }

    /**
     * Determine whether the user can delete the product.
     *
     * @param User    $user
     * @param Product $product
     *
     * @return mixed
     */
    public function delete(User $user, Product $product)
    {
        if ($user->can('delete any product'))
            return true;

        if ($user->can('delete own product'))
            return $user->id === $product->user_id;
    }

    /**
     * Determine whether the user can restore the product.
     *
     * @param User    $user
     * @param Product $product
     *
     * @return mixed
     */
    public function restore(User $user, Product $product)
    {
        if ($user->can('restore any product'))
            return true;

        if ($user->can('restore own product'))
            return $user->id === $product->user_id;
    }

    /**
     * Determine whether the user can permanently delete the product.
     *
     * @param User    $user
     * @param Product $product
     *
     * @return mixed
     */
    public function forceDelete(User $user, Product $product)
    {
        if ($user->can('forceDelete any product'))
            return true;

        if ($user->can('forceDelete own product'))
            return $user->id === $product->user_id;
    }
}
