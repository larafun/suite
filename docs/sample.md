## Description

To better demonstrate the usage of different modules that make up this package we will use a sample application that will cover various needs.

The app will consist of these models: `Author`, `Post`, `Comment`.

## Installation and Setup

```bash
laravel new project \
&& cd project \
&& composer require larafun/suite
```

Create a database for this project and update your `.env` file accordingly.

## Posts

`php artisan build:model Post -a`

This will create for us the following:

* `App\Models\Post`
* `database/factories/PostFactory.php`
* `database/migrations/yyyy_mm_dd_hhiiss_create_posts_table.php`
* `App\Http\Controllers\Api\PostController`
* `App\Filters\PostFilter`
* `App\Http\Resources\PostResource`

**The Post Model**

```php
class Post extends Model
{
    use ValidatableTrait;

    protected $fillable = [
        'author_id',
        'title',
        'body'
    ];

    /**
     * Rules
     */
    public function savingRules()
    {
        return [
            'author_id'     => 'required|numeric',
            'title'         => 'required|min:2',
            'body'          => 'required|min:5'
        ];
    }

    /**
     * Relationships
     */
    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    /**
     * Query scopes
     */
    public function scopeFilter($query, PostFilter $filter)
    {
        return $query
            ->writtenBy($filter->$author_ids)
            ->containing($filter->search)
            ->skip($filter->page * $filter->size)
            ->take($filter->size)
        ;
    }

    public function realScopeWrittenBy($query, $author_ids)
    {
        return $query->whereIn('author_id', $author_ids);
    }

    public function realScopeContaining($query, $search)
    {
        return $query->where(function ($query) {
            return $query->where('title', 'like', "%$search%")
                ->orWhere('body', 'like', "%$search%");
        });
    }

    /**
     * Presentation
     */
    public function getResource()
    {
        return PostResource::class;
    }
}
```

**The Post Controller**

```php
class PostController extends Controller
{
    /**
     * $filter will be automatically constructed with the
     * request parameters
     */
    public function index(BookFilter $filter)
    {
        /**
         * This will return a json response with pagination information
         */
        return Post::filter($filter)->get();
    }

    public function store(Request $request)
    {
        /**
         * Validation occurs when actually creating the Post
         * If any errors are found, a ValidationException will be thrown
         */
        return Post::create($request->all());
    }

    public function show(Post $post)
    {
        /**
         * The resource will be automatically transformed with the rules
         * defined in the PostResource
         */
        return $post;
    }

    public function update(Post $post, Request $request)
    {
        /**
         * Validation occurs when updating the resource according
         * to the rules specified on the Model
         */
        return $post->update($request->all());
    }

    // ...
}
```

**The Post Resource**

```php
class PostResource extends Resource
{
    /**
     * Relations will stop loading after this depth.
     * This way infinite loops or extra large
     * responses may be avoided.
     * The deepen() method needs to be used on these
     * relations for this behavior the take effect
     */
    protected $max_depth = 3;

    public function boot()
    {
        /**
         * This resource may be used on a Model or on a Collection.
         * Using collected() we can wrap the Model in a Collection.
         */
        $posts = $this->collected();
        
        /**
         * The eager loading takes place only when booting the Resource.
         * This will happen when the app is ready to respond and the
         * response is ready to be built.
         */
        $posts->load('author', 'comments');
    }

    public function item(Post $post)
    {
        return [
            'id'        => $post->id,
            'author'    => $this->deepen($post->author),
            'title'     => $post->title,
            'body'      => $post->body,
            'comments'  => $this->deepen($post->comments)
        ];
    }
}
```

**The Post Filter**
```php
class PostFilter extends Filter
{
    public function defaults(): array
    {
        return [
            'search'        => null,        // searching through title and body
            'author_ids'    => [],          // only posts written by the given authors
            'page'          => 0,
            'size'          => 20
        ];
    }

    /**
     * This method is optional, but when defined, it will apply the rules 
     * against the constructor arguments
     */
    public function rules(): array
    {
        return [
            'size'  => 'numeric|max:100',   // avoid sending too large responses
        ];
    }
}
```

