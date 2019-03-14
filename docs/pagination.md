Pagination is included by default by the Larafun Suite package.

## Format

In order to change the keys and format of a paginator, simply extend an existing one.

```php
class CustomPaginator extends CountPaginator
{
    /**
     * The pagination information
     */
    public function pagination(): array
    {
        return [
            'current_page'  => $this->page(),
            'page_length'   => $this->size(),
            'total_results' => $this->count(),
            'total_pages'   => $this->pages(),
        ];
    }
}
```

In order to use it through out the application, declare it in your `config/suite.php` file. Due to the way the pagination is computed, custom paginators cannot be used during runtime.
