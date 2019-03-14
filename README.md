# Larafun Suite

[![Build Status](https://travis-ci.org/larafun/suite.svg?branch=master)](https://travis-ci.org/larafun/suite)
[![Docs Status](https://readthedocs.org/projects/larafun-suite/badge/?version=latest)](https://larafun-suite.readthedocs.io)
[![Latest Stable Version](https://poser.pugx.org/larafun/suite/v/stable)](https://packagist.org/packages/larafun/suite)
[![License](https://poser.pugx.org/larafun/suite/license)](https://packagist.org/packages/larafun/suite)
[![Total Downloads](https://poser.pugx.org/larafun/suite/downloads)](https://packagist.org/packages/larafun/suite)

A small collection of classes and traits that will make your Laravel API development even more awesome!

Check out the full documentation in here [larafun-suite.readthedocs.io](https://larafun-suite.readthedocs.io)

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

- [x] Add Validation to Filters
- [ ] Add Formatters to Filters
- [ ] Add Sanitizers to Filters
- [ ] Change Filters behaviour to allow keys that do not have any defined defaults
- [ ] Allow Filters to override values after instatiation
- [ ] Split Filters into a separate package

