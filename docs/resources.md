The Larafun Resources are meant to extend the Laravel Resources, make them easier to use and extend their behaviour

## Advantages

* Associate each Model with a default Resource to avoid wrapping it with every Response
* Create unique Resources for both Models and Eloquent Collections of the same Model
* An easy way to define where to place the pagination details
* Control the maximum depth of included Resources
* An easy way to define custom behaviour before transforming resources (loading relationships or other meta data)

## Transformation

Unlike the default Laravel Resource, you will use the `item()` method instead of `toArray()`. This is because the original `toArray()` method has been overwritten to allow both Models and Eloquent Collections to be handled by the same resource.

Moreover, the `item()` method allows type hinting the resource that is being transformed.

The undefined methods will be delegated to the underlying resource, but since both a Model and a Collection can be decorated with Larafun Resources, such delegations should be carefully treated.

```php
use App\Models\Book;

class BookResource extends Resource
{
    public function item(Book $book)
    {
        return [
            'id'    => $book->id,
            'title' => $book->title,
            'year'  => $book->publishing_year
        ];
    }
}
```

## Association

For each Larafun Model, a default resource can be associated. The Models also implement the `Responsable` interface, meaning that they will be mapped to a Response.

```php
use Larafun\Suite\Model;

class Book extends Model
{
    public function getResource()
    {
        return BookResource::class;
    }
}
```

In your Controller, you can now just return the Model or the Collection and they will be automatically wrapped by the Resource before responding.

```php
class BookController extends Controller
{
    public function index()
    {
        /**
         * All elements in the Collection will be transformed by the
         * default Resource. Pagination will also be included before
         * responding
         */
        return Book::all();
    }

    public function show(Book $book)
    {
        /**
         * The Model will be automatically transformed by the
         * default resource
         */
        return $book;
    }
}
```

## Swapping Resources

Swapping Resources happens just as you would normally use a Laravel Resource, by wrapping your Model.

```php
class SummaryResource extends Resource
{
    public function item(Book $book)
    {
        return [
            'id'    => $book;
            'title' => $book->title;
        ];
    }
}
```

```php
class BookController extends Controller
{
    public function index()
    {
        /**
         * Build the resource with a static method
         */
        return SummaryResource::make(Book::all());
    }

    public function show(Book $book)
    {
        /**
         * Build the resource through the constructor
         */
        return new SummaryResource($book);
    }
}
```

When instantiating Resources you may want to also control their behaviour.

To specifically request a Collection transformation you may build your resource using the `collection` method: `SummaryResource::collection(Book:all())`

To specifically request an item transformation you may build your resource using the `resource` method: `SummaryResource::resource($book)`

By default, the Resources will try to identify the proper way to transform their data checking wether the data implements the `Illuminate\Support\Collection` or not.

## Depth control

When working with a lot of relations and including some of them in the Resource, there is always a risk of loading too much data or, even worse, generate infinite loops.

Larafun Resources control this behaviour through the `$max_depth` property and the `deepen()` method.

**Post**
```php
class PostResource extends Resource
{
    protected $max_depth = 3;

    public function item(Post $post)
    {
        return [
            'id'        => $post->id,
            'author'    => $this->deepen($post->author)
        ];
    }
}
```

**Author**
```php
class AuthorResource extends Resource
{
    protected $max_depth = 5;

    public function item(Author $author)
    {
        return [
            'id'        => $author->id,
            'posts'     => $this->deepen($author->posts)
        ];
    }
}
```

The above example is a simple example of how an infinite loop can be created. By using the `$max_depth` property and the `deepen()` method this behaviour is avoided.

When building a Resource only the top level `$max_depth` property will be considered. Hence, if the Controller will respond with a `Post`, it will return the `Post`, its `Author` and all the Author's `Posts` (depth of 3). If the Controller will respond with an `Author`, the response depth will be 5.

The `deepen()` method accepts as arguments a Model, an Eloquent Collection or another Larafun Resource. If the argument is a Model or an Eloquent Collection it will use the default Resource defined on the Model to decorate it.

## Pagination

Larafun Resources include pagination information for all `Paginatable` Collections. By default, all Larafun Models will return `Paginatable` Collections when queried.

By default, the pagination details will be included at the root level of the json. If you want to change that behaviour, you can adapt your resources:

```php
class PaginationResource extends Resource
{
    protected function pagination(): array
    {
        return [
            'meta' => [
                'pagination' => parent::pagination()
            ]
        ];
    }
}
```

If you want a consistent behaviour throughout your application, you can create a custom Resource that defines the pagination behaviour and extend it in your models. You can also specify it in the `config/suite.php` file so that your custom Resources automatically extend it when they are created.

Larafun Suite also provides a `MetaPaginationResource` that includes the pagination details inside the `meta` property.

## Append information

Sometimes you may want additional information to be sent out to the response. Use the `append` method to define your data.

```php
class DataResource extends Resource
{
    public function append(): array
    {
        return [
            'meta' => [
                'stamp' => microtime(true)
            ]
        ];
    }
}
```


## Properties
**Data**

The decorated object is always accessible inside the Resource class through the `resource` property: `$this->resource`.

Since the decorated object can be a Model or a Collection, it is safer to retrieve it using the `collected()` method, which will always return a Collection (of one item if the decorated object is a Model).

**Request**

In both `boot()` and `item()` methods the Request is accessible as a Resource property, using `$this->request`.

## Additional data

At any point inside your application you have access to the underlying resource through the `resource()` method.

```php
class BookController extends Controller
{
    public function index()
    {
        return Book::all()->resource()->additional(['foo' => 'bar']);
    }

    public function show(Book $book)
    {
        return $book->resource()->additional(['foo' => 'bar']);
    }
}
```
