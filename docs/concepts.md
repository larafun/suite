We present below the main concepts that the Larafun Suite relies on.

## Resources

Larafun Suite extends the default [Laravel Resources](https://laravel.com/docs/5.8/eloquent-resources) to enhance their behaviour and facilitate their usage.

Use the following command to generate a Larafun Suite Resource:

`php artisan build:resource BookResource`

## Filters

Filters provide an easy way to instantiate and type hint a set of parameters. They will also validate their values and use predefined defaults when not values are not provided.

We list below a simple scenario. Please consult the more detailed section on [Filters](/filters).
```php
class BookController extends Controller
{
    /**
     * The filter is instantiated using the values from the Request
     */
    public function index(BookFilter $filter)
    {
        return Book::filter($filter)->get();
    }
}
```

```php
class BookFilter extends Filter
{
    /**
     * When certain values are missing, the defaults will be used
     */
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
    /**
     * The $filter object can now be type hinted into the Model scopes
     */
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

Eloquent Collections for models that extend the `Larafun\Suite\Model` will allways include a Paginator. You can define your own to replace the default or use the `NullPaginator` provided with the package if you don't want to use Pagination on your Collections.

## Real Scopes

[Laravel local query scopes](https://laravel.com/docs/5.8/eloquent#query-scopes) are a great way to define constraints to be used when querying your Models. Sometimes, you need to apply a scope only when a given parameter is not empty and Laravel allows you to use the `when()` conditional clause to check that inside your scope.

On the other hand, Real Scopes apply only when none of the parameters are empty. This allows the creation of scopes that are much more easier to define and read.

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
