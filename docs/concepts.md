We present below the main concepts that the Larafun Suite relies on.

## Resources

Larafun Suite extends the default [Laravel Resources](https://laravel.com/docs/5.8/eloquent-resources) to enhance their behaviour and facilitate their usage.

Use the following command to generate a Larafun Suite Resource:

`php artisan build:resource BookResource`

## Filters

Filters are meant to be used as containers for all parameters that you use in order to query a Model. They can be typehinted in a Controller method and they will be automatically filled with the request data, if present. You can easily define default values for parameters that are not present in the request.

One other advantage for using filters is that you can use them in contexts that do not rely on the HTTP Request to pass the query parameters, such as in Commands. They work seamlessly when used with query scopes.

```php

class BookController extends Controller
{
    public function index(BookFilter $filter)
    {
        return Book::filter($filter)->get();
    }
}
```

```php

class BookFilter extends Filter
{
    public function defaults(): array
    {
        return [
            'search'    => null,
            'before'    => 2000,
            'skip'      => 0,
            'take'      => 5
        ];
    }
}
```

```php

class Book extends Model
{
    public function scopeFilter($query, BookFilter $filter)
    {
        return $query
            ->search($filter->search)
            ->before($filter->before)
            ->skip($filter->skip)
            ->take($filter->take)
        ;
    }

    public function realScopeSearch($query, $search = null)
    {
        return $query->where('title', 'like', "%$search%");
    }

    public function scopeBefore($query, $year)
    {
        return $query->where('publishing_year', '<=', $year);
    }
}
```

## Paginators

The Larafun Suite Paginators are custom objects and don't rely on Laravel's default paginators. They are much more easier to extend and replace and they rely exclusively on the executed query in order to compute their values. Also, it is a lot easier to define their keys and their position in the json response.

In addition, the Larafun Suite Paginators will execute the `count` query only when responding with a resource that needs to be paginated, instead of when collecting the results from the database as the default Laravel paginators.

By default, Eloquent Collections for models that extend the `Larafun\Suite\Model` will include a Paginator.

## Real Scopes

[Laravel local query scopes](https://laravel.com/docs/5.8/eloquent#query-scopes) are a great way to define constraints to be used when querying your Models. Sometimes, you need to apply a scope only when a given parameter is not empty and Laravel allows you to use `when` conditional clause to check that inside your scope.

On the other hand, Real Scopes apply only when none of the parameters are empty. This allows easier to define and to read scopes.

```php

class Book extends Model
{
    /**
     * Checking the parameters with when
     */
    public function scopeShorterThan($query, $pages = null)
    {
        return $query->when($pages, function ($query, $pages) {
            return $query->where('pages', '<=', $pages);
        });
    }

    /**
     * Using real scopes to simplify your code
     */
    public function realScopeLongerThan($query, $pages = null)
    {
        return $query->where('pages', '>=', $pages);
    }
}
```
