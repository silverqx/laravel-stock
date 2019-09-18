<?php

namespace App\Http\Controllers;

use Route;

use App\Http\Requests\Product\ListingRequest;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Modules\Product\Product;
use App\Services\Product\ListingService;

class ProductController extends Controller
{
    /**
     * Listing Products Service.
     *
     * @var ListingService
     */
    private $listingService;

    /**
     * ProductController constructor.
     *
     * @param ListingService $listingService Listing Products Service.
     */
    public function __construct(ListingService $listingService)
    {
        $this->authorizeResource(Product::class);

        $this->listingService = $listingService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ListingRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(ListingRequest $request)
    {
        $search = $request->query('search');
        $orderBy = $request->query('orderBy', ['id']);
        $direction = $request->query('direction', 'asc');

        // Paginated Products
        $products = $this->listingService->getProducts($search, $orderBy, $direction);

        $currentRouteName = Route::currentRouteName();
        $currentPage = $products->currentPage();

        // User's Products Count
        $userProductsCount = $this->listingService->userProductsCount($request->user()->id);
        // Should Hide Actions Column?
        $hideActions = $this->listingService->hideActions($request->user(), $userProductsCount);

        return view('product.index',
            compact('products', 'search', 'orderBy', 'direction', 'currentRouteName',
                'currentPage', 'hideActions'
            )
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
