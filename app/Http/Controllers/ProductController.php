<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Modules\Product\Product;

class ProductController extends Controller
{
    /**
     * Validation rules for a Product.
     *
     * @var array
     */
    private $productRules = [
        'name'     => ['required', 'min:2', 'max:255', 'string', 'regex:/^[\w\-_\ ]+$/'],
        'position' => 'required|integer|min:0',
        'balance'  => 'required|integer|min:0',
        // Also check config/medialibrary.php max_file_size
        'image'    => 'required|image|mimes:jpeg,png,gif|max:2048'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();

        $media = $products->first()->getFirstMedia('products')('thumb-40');

        return view('product.index', compact('products', 'media'));
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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->productRules);

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
     * @param \Illuminate\Http\Request     $request
     * @param \App\Modules\Product\Product $product
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, $this->productRules);

        $product
            ->fill($request->all())
            ->save();

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

        \Storage::disk('public')->deleteDirectory($product->id);

        return redirect()->route('products.index');
    }
}
