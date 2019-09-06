<?php

use Faker\Generator as Faker;

use App\Modules\Product\Product;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Product::class, function (Faker $faker) {
    static $id = 1;

    return [
        'name'     => 'Product ' . $id++,
        'balance'  => $faker->numberBetween(0, 10),
        'position' => $faker->numberBetween(1, 3),
    ];
});
