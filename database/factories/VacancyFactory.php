<?php

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

$factory->define(App\Vacancy::class, function (Faker $faker) {

    return [
        'title' => $faker->jobTitle,
        'description' => $faker->text(5000),
        'address' => $faker->streetName,
        'complement' => '',
        'number' => $faker->randomNumber(4),
        'city' => $faker->city,
        'salary' => $faker->randomFloat(2),
        'state' => $faker->stateAbbr,
        'status' => $faker->randomElement($array = array('open', 'closed')),
        'zipcode' => $faker->postcode
    ];
});
