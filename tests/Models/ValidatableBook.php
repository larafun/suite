<?php

namespace Larafun\Suite\Tests\Models;

use Larafun\Suite\Traits\ValidatableTrait;

class ValidatableBook extends Book
{
    use ValidatableTrait;

    protected $guarded = [];

    public function savingRules()
    {
        return [
            'author'    => 'string|min:2',
            'title'     => 'string|min:2',
            'published' => 'numeric|min:1900|max:2010',
        ];
    }

    public function creatingRules()
    {
        return [
            'author'    => 'string|min:12',
            'title'     => 'string|min:2',
            'published' => 'numeric|min:1800|max:2010',
        ];
    }

    public function updatingRules()
    {
        return [
            'author'    => 'string|min:2',
            'title'     => 'string|min:2',
            'published' => 'numeric|min:1950|max:2020',
        ];
    }
}
