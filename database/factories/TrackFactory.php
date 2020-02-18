<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Track;
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

$factory->define(Track::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'album_id' => $faker->numberBetween(0,9),
        'Mediatype_id' => $faker->numberBetween(0,9),
        'genre_id' => $faker->numberBetween(0,9),
        'composer' => $faker->name,
        'milliseconds' => $faker->numberBetween(0,9),
        'bytes' => $faker->numberBetween(0,9),
        'unit_price' => $faker->numberBetween(0,9)
    ];
});
