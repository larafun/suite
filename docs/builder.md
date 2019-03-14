Sometimes you may want to return database queries inside a `data` wrapper and add `meta` or `pagination` information.

You can easily convert your results into a default Resource using the `resource` macro on the Query Builder. This will return a ResourceableCollection with your results.
The `resource` macro has the same signature as the `get` method.

```php

public function index()
{
    return DB::table('authors')->where('name', 'like' , '%jim%')->resource();
}
```

You can chain the `additional()` method if you need to attach other details to the response.

---

**This macro is not defined on the Eloquent Query Builder!**
