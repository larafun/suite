<?php

namespace Larafun\Suite\Tests;

use Faker\Generator as Faker;

$factory->define(Models\Book::class, function (Faker $faker) {
    return [
        'author'    => $faker->name,
        'title'     => $faker->sentence,
        'published' => $faker->year,
    ];
});
