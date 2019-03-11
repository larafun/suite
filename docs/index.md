A collection of classes and traits that will make your Laravel API workflow even more awesome!

## General description

The Larafun Suite package allows to define default [Resources](concepts#resources) that will decorate your Models or Elquent Collections, so that you can directly return your objects from the Controllers.

Once you define your decorating Resource inside your model:

```php

use Larafun\Suite\Model;
use App\Http\Resources\BookResource;

class Book extends Model
{
    //...

    public function getResource()
    {
        return BookResource::class;
    }
}
```

It will be automatically used to transform the model every time you return it from your controller. The same resource will be automatically used to transform every model inside an Eloquent collection.

```php

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        /**
         * It will apply the Resource transformation for each Book in the Collection
         * It will also add meta information about pagination.
         */
        return Book::all(); 
    }

    public function store(Request $request)
    {
        /**
         * The newly created model will be transformed before converting it to JSON
         */
        return Book::create($request->all());
    }

    public function show(Book $book)
    {
        /**
         * The model will be transformed by its Resource
         */
        return $book;
    }
}
```


## Installation

```bash
composer require larafun/suite
```

## Basic Usage

Create a new Model, with its Filter and Resource:

`php artisan build:model Book -fr`

Create a new Model, with its Filter, Resource, Factory, Migration and Controller:

`php artisan build:model Book -a`

