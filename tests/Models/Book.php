<?php

namespace Larafun\Suite\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Larafun\Suite\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'test_books';


    protected static function newFactory()
    {
        return \Larafun\Suite\Tests\Database\Factories\BookFactory::new();
    }
}
