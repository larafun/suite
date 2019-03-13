Filters are meant to be used as containers (Value Object) for all parameters that you use in order to query a Model.

## Advantages

* You can type hint them in your query methods and avoid checking for valid values
* You can construct them by type hinting them in the Controller methods
* You can instantiate them with arrays and have a unique and consistent behaviour throughout your application
* You can easily define default values for your Filters
* You can apply validation rules

## Defining Filters

To create a new filter you need only to extend the `Larafun\Suite\Filters\Filter` and define a `defaults()` public method.

You can also generate a new filter by running `php artisan build:filter BookFilter`

## Default values

Default values are used when no value has been provided for the keys that define the filter.

They are also a great place to describe the expected behaviour when applying a particular filter.

```php
use Larafun\Suite\Filters\Filter;

class BookFilter extends Filter
{

    public function defaults(): array
    {
        return [
            'author_id'         => null,        // Retrieve only the books written by this Author
            'category'          => 'any',       // Retrieve only books from this category
            'published_before'  => null,        // Retrieve only the books that have been published before this year
            'skip'              => 0,           // Skip this amount of books from the query
            'take'              => 10           // Return only this amount of books
        ];
    }
}
```

Even though the Request may contain additional parameters, only the fields defined by the `defaults()` method will be considered when instantiating the Filter.
This behaviour is desirable so that developers won't rely on properties that have not been defined and documented.

## Validation rules

Sometimes you want to make sure that some filters pass a set of validation rules. All you need to do is define a `rules()` method.

```php
use Larafun\Suite\Filters\Filter;

class BookFilter extends Filter
{

    public function rules(): array
    {
        return [
            'author_id'         => 'numeric',
            'category'          => 'in:any,poetry,history,psychology',
            'published_before'  => 'numeric|min:1860|max:2010',
            'skip'              => 'numeric',
            'take'              => 'numeric|max:50'
        ];
    }
}
```

## Instantiation

A filter may be easily constructed with an array with key value pairs. The missing fields will be filled with their default value:

```php
$filter = new BookFilter([
    'author_id'         => 12,
    'published_before'  => 1978
]);

echo $filter->author_id;
// 12

echo $filter->published_before;
// 1978

echo $filter->category
// any

echo $filter->take
// 10
```

A filter may be also passed as a dependency in a Controller and it will be build using the Request parameters.

```php

use App\Filters\BookFilter;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    public function index(BookFilter $filter)
    {
        echo $filter->take;
        // 10
    }
}
```

In any of these cases, the validation rules are being applied against the provided values and a `ValidationException` will be thrown on failure.

## Using a Filter

The best way of using a Filter is passing it as a parameter on a general query scope.

```php
use Larafun\Suite\Model;

class Book extends Model
{
    public function scopeFilter($query, BookFilter $filter)
    {
        return $query
            ->byAuthor($filter->author_id)
            ->before($filter->published_before)
            ->skip($filter->skip)
            ->take($filter->take)
        ;
    }

    public function realScopeByAuthor($query, $author_id = null)
    {
        return $query->where('author_id', $author_id);
    }

    public function scopeBefore($query, $year = null)
    {
        return $query->where('publishing_year', '<=', $year);
    }
}
```

## Real case scenario

Considering the above examples, your Controller should now look like this:

```php

use App\Models\Book;
use App\Filters\BookFilter;
use App\Http\Controllers\Controller;

class BookController extends Controller
{
    public function index(BookFilter $filter)
    {
        return Book::filter($filter)->get();
    }
}
```

Bear in mind that your Response will also benefit of automatic pagination and Resource transformation if the `Book` model will extend the `Larafun\Suite\Model`
