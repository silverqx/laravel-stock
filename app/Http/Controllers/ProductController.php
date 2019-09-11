<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Modules\Product\Product;

class ProductController extends Controller
{
    /**
     * Validation rules for create a Product.
     *
     * @var array
     */
    private $storeRules = [
        'name'     => ['required', 'min:2', 'max:255', 'string', 'regex:/^[\pL\-_\ ]+$/u'],
        'position' => 'required|integer|min:0',
        'balance'  => 'required|integer|min:0',
        // Also check config/medialibrary.php max_file_size
        'image'    => 'required|image|mimes:jpeg,png,gif|max:2048'
    ];

    /**
     * Validation rules for update a Product.
     *
     * @var array
     */
    private $updateRules = [
        'name'     => ['required', 'min:2', 'max:255', 'string', 'regex:/^[\pL\-_\ ]+$/u'],
        'position' => 'required|integer|min:0',
        'balance'  => 'required|integer|min:0',
        // Also check config/medialibrary.php max_file_size
        'image'    => 'image|mimes:jpeg,png,gif|max:2048'
    ];

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $products = Product::where('name', 'like', "%$search%")->paginate(10);

        return view('product.index', compact('products', 'search'));
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
        $this->validate($request, $this->storeRules);

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
        $this->validate($request, $this->updateRules);

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

        \Storage::disk('public')->deleteDirectory($product->id);

        return redirect()->route('products.index');
    }
}
