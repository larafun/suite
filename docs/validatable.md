Laravel provides an elegant way to validate the requests. Using this approach, when you create a new model that requires validation, you are instructed to create a new form request and define the validation rules on the request.

## Advantages

* Validation takes place at Model level not at Request level
* Same validation rules can applied for other contexts (Console Commands)
* Defining all rules in the same place (creating, updating) instead of creating new files for different types of requests and duplicating rules.
* Being defined as methods, they allow values to be computed at runtime.
* Being defined as public, the rules may be retrieved in other contexts as well.
* Being declared as instance methods provide access to other object properties or methods.

## Defining Rules

All you need to do is use the `ValidatableTrait` on your models and define the appropriate methods for validation, depending on the situation you want to apply them. At this moment, Larafun Suite supports 3 types of rules:
* `creatingRules()` are applied when creating a new Model
* `updatingRules()` are applied when updating an existing Model
* `savingRules()` are applied when creating or updating a Model.

```php

use Larafun\Suite\Traits\ValidatableTrait;

class Book extends Model
{
    use ValidatableTrait;

    public function savingRules()
    {
        return [
            'author'    => 'string|min:5',
            'title'     => 'string|min:5',
        ];
    }

    public function creatingRules()
    {
        return [
            'published' => 'numeric|min:1800|max:2020',
        ];
    }

    public function updatingRules()
    {
        return [
            'published' => 'required|numeric|min:1800|max:2020',
        ];
    }
}
```

With the above setup, the rules for `author` and `title` will apply both on creating and updating. The `published` needs to have a numeric value between 1800 and 2020, but while it's optional when creating a new resource, it will be required when updating.

All methods are optional. You need to define only the ones that you actually need to use in your scenarios.

