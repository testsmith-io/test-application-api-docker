<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Invoiceline;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Invoiceline::class, function (Faker $faker) {
    return [
        'invoice_id' => $faker->numberBetween(0, 9),
        'track_id' => $faker->numberBetween(0, 9),
        'unit_price' => $faker->numberBetween(0, 9),
        'quantity' => $faker->numberBetween(0, 9)
    ];
});
