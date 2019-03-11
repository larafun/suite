<?php

namespace Larafun\Suite\Tests\Models;

use Larafun\Suite\Tests\Stubs\FirstResourceStub;

class TransformedBook extends Book
{
    public function getResource()
    {
        return FirstResourceStub::class;
    }
}
