# Larafun Suite
A small collection of classes and traits that will make your Laravel development even more awesome!

## Installation

Requires PHP > 7.0, Laravel > 5.5

```bash
composer require larafun/suite
```

## Basic Usage

Just make your Models extend `Larafun\Suite\Model` instead of `use Illuminate\Database\Eloquent\Model`

```php
<?php

namespace App\Models;

use Larafun\Suite\Model;

class Book extends Model
{

}
```

Now you can use your models as you regularly would.

## Why use this package?

Because you get enhanced **Resources** and **Pagination** out of the box!

```php
class BookController extends Controller
{
    // ...

    public function index()
    {
        return Book::where('author', 'Isaac Asimov')->skip(2)->take(5)->get();
    }

```

Since the `Book` is an instance of `Larafun\Suite\Model` the results will be
automatically presented inside a `data` property. Additionally, a `meta` field
will be included and provide `pagination` information out of the box.

### Switch back to the default behaviour?

The most easy way is to make your models extend `Illuminate\Database\Eloquent\Model`.

Otherwise you can fine tune your setup inside `config/suite.php`, after you publish it:

`php artisan vendor:publish --provider=Larafun\\Suite\\ServiceProvider`

## TODO

These things should be added:

- [ ] Meta Aware Presentables
- [ ] Paginators position (inside meta or at root level)
- [ ] Customizable meta and data keys (presenter level, presentable level, app level)
- [ ] Artisan commands to make Transformers and Presenters
- [ ] Global meta awareness
- [ ] Configurable defaults for: Presenter keys, global Meta
- [ ] Add Validation to Filters
- [ ] Add Formatters to Filters
- [ ] Split Filters into a separate package

## Refactoring

We aim for the following functionalities

 - [ ] Respond with models
 - [ ] Respond with paginatable Eloquent collections
 - [ ] An easy way to paginate DB queries
 - [ ] An easy way to change the default behaviour:
    - meta keys
    - data keys
    - pagination keys
    - pagination position
    - default transformer
    - default paginator
    - default Eloquent Collection
 - [ ] An easy way to build files
    - models
    - filters
    - resources
- [ ] Grooming
    - Rename the Eloquent Collection
    - ResourceableTrait: Use a configurable resource
      - Use the Resourceable trait in the Collection?
    - Builder: registerRealScopes
    - Query Builder: register macro to return a Resourceable Collection
    - Drop the Queryable Trait?
    - Drop the Paginatable Trait?
    - Drop the ResourceCollection and CollectionResource in favour of Resource
    - Do we need the PaginationFactory?
    - Filters add Formatters and Sanitizers
    - Contracts - do we need them all?
    - config - check comments and adapt
    - check that all commands are working properly
    - PresentableCollection - rename it
    - composer - check that all dependencies are met
    - have a custom Resource to be used when generating new Resources

- [ ] Tests
    - ResourceableCollection
    - Commands
    - Paginators
    - Resources

- [ ] Find a good way to apply the paginator.
- [ ] Fix the failing tests.
    - Build tests for the new features

- [ ] Read the docs
    - Add plenty of examples and use cases


### Discovered when writing docs
 - PostResource->item cannot type hint, as it's not conform with the parent implementation
 - Pagination should have its own keys parent keys (inside the paginator or inside the resource...)
 

