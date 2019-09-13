<?php

namespace App\Http\Controllers;

use Route;

use App\Http\Requests\Product\ListingRequest;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Modules\Product\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ListingRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListingRequest $request)
    {
        $search = $request->query('search');
        $orderBy = $request->query('orderBy', ['id']);
        $direction = $request->query('direction', 'asc');

        $products = Product::where('name', 'like', "%$search%")
            ->tap(function ($query) use ($orderBy, $direction) {
                collect($orderBy)
                    ->each(function ($item) use ($query, $direction) {
                        $query->orderBy($item, $direction);
                    });
            })
            ->paginate(20)
            ->appends(compact('search', 'orderBy', 'direction'));

        $currentRouteName = Route::currentRouteName();
        $currentPage = $products->currentPage();

        return view('product.index',
            compact('products', 'search', 'orderBy', 'direction', 'currentRouteName', 'currentPage')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    public function store(StoreRequest $request)
    {
        /** @var Product $product */
        $product = Product::create($request->all());

        $product->addMediaFromRequest('image')
            ->toMediaCollection('products');

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Modules\Product\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Modules\Product\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest                $request
     * @param \App\Modules\Product\Product $product
     *
     * @return \Illuminate\Http\Response
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\FileIsTooBig
     */
    public function update(UpdateRequest $request, Product $product)
    {
        $product
            ->fill($request->all())
            ->save();

        if ($request->has('image'))
            $product->addMediaFromRequest('image')
                ->toMediaCollection('products');

        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Modules\Product\Product $product
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Product $product)
    {
        $product->delete();

        // Do not delete, because soft delete model enabled
//        \Storage::disk('public')->deleteDirectory(
//            $product->media->first()->getKey()
//        );

        return redirect()->route('products.index');
    }
}
