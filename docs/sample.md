## Description

To better demonstrate the usage of different modules that make up this package we will use a sample application that will cover various needs.

The app will consist of these models: `User`, `Post`, `Comment`.

## Installation and Setup

```bash
laravel new sample \
&& cd sample \
&& composer require larafun/suite
```

Create a database for this project and update your `.env` file accordingly.

*We moved the `User` inside the `App\Models` namespace. (changes needed to be made in the User class, in config/auth.php and in UserFactory.php*

## First custom model

`php artisan build:model Post -a`

This will create for us the following:

* `App\Models\Post`
* `database/factories/PostFactory.php`
* `database/migrations/yyyy_mm_dd_hhiiss_create_posts_table.php`
* `App\Http\Controllers\Api\PostController`
* `App\Filters\PostFilter`
* `App\Http\Resources\PostResource`

Build the migration and factory so that they have the following fields: `author_id`, `title`, `body`.

Add your routes in `routes/api.php`: `Route::apiResource('posts', 'Api\\PostController')`.

Inside your `Api\PostController` handle the `index` method:

```php
public function index()
{
    return Post::all();
}
```

When calling this method you will instantly see that the results are automatically wrapped around a `data` key and that `pagination` is included out of the box for you.

---

Let's now return a `Post`:

```php
public function show(Post $post)
{
    return $post;
}
```

The response will also be wrapped around a `data` key.

---

To change the way the `Post` is presented, change your `App\Http\Resources\PostResource`:

```php
class PostResource extends Resource
{
    public function item($post)
    {
        return [
            'id'        => $post->id,
            'author_id' => $post->author_id,
            'title'     => $post->title,
            'summary'   => Str::limit($post->body, 20)
        ];
    }
}
```

Now, both your `show` and `index` methods on the `PostController` will return the data transformed accordingly.

## Adding relationships





