<?php

namespace Larafun\Suite\Tests\Database\Factories;

use Larafun\Suite\Tests\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition()
    {
        return [
            'author'    => $this->faker->name,
            'title'     => $this->faker->sentence,
            'published' => $this->faker->year,
        ];
    }
}
