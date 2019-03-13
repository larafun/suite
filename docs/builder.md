You can use the `resource` macro on the Query Builder to obtain a ResourceableCollection with your results.
The `resource` macro has the same signature as the `get` method.

```php

public function index()
{
    return DB::table('authors')->where('name', 'like' , '%jim%')->resource();
}
```

This macro is not defined on the Eloquent Query Builder.
