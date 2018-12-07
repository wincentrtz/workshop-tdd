<?php

use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'product_name' => $faker->word,
        'product_description' => $faker->text
    ];
});
