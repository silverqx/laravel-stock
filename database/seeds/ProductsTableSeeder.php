<?php

use Illuminate\Database\Seeder;

use App\Modules\Product\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productImages = Storage::disk('seed_images')->files('products');

        factory(Product::class, 12)
            ->create()
            ->each(function ($product) use (&$productImages) {
                /** @var Product $product */
                $product
                    ->addMedia(
                        Storage::disk('seed_images')->path(
                            array_shift($productImages)
                        )
                    )
                    ->preservingOriginal()
                    ->toMediaCollection('products');
            });
    }
}
