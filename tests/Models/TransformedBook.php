<?php

namespace Larafun\Suite\Tests\Models;

use Larafun\Suite\Tests\Stubs\FirstTransformerStub;

class TransformedBook extends Book
{
    public function getTransformer()
    {
        return FirstTransformerStub::class;
    }
}