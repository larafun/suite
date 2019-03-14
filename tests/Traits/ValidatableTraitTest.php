<?php

namespace Larafun\Suite\Tests\Traits;

use Larafun\Suite\Tests\DataTestCase;
use Illuminate\Validation\ValidationException;
use Larafun\Suite\Tests\Models\ValidatableBook;

class ValidatableTraitTest extends DataTestCase
{
    /** @test */
    public function itFailsOnCreating()
    {
        $this->expectException(ValidationException::class);
        ValidatableBook::create([
            'author'    => 'Mark Twain',            // this should fail creating rules
            'title'     => 'Adventures of Huckleberry Finn',
            'published' => '1920'
        ]);
    }

    /** @test */
    public function itValidatesOnCreatingButFailsOnSaving()
    {
        $this->expectException(ValidationException::class);
        ValidatableBook::create([
            'author'    => 'Mark Twain Jr',         // this should pass creating rules
            'title'     => 'Adventures of Huckleberry Finn',
            'published' => '1884'                   // this should fail saving rules
        ]);
    }

    /** @test */
    public function itFailsOnUpdating()
    {
        $book = ValidatableBook::create([       // this should pass
            'author'    => 'Mark Twain Jr',
            'title'     => 'Adventures of Huckleberry Finn',
            'published' => '1910'
        ]);

        $this->expectException(ValidationException::class);
        $book->update([
            'published' => '1920'               // this should fail updating rules
        ]);
    }

    /** @test */
    public function itValidatesOnUpdatingButFailsOnSaving()
    {
        $book = ValidatableBook::create([       // this should pass
            'author'    => 'Mark Twain Jr',
            'title'     => 'Adventures of Huckleberry Finn',
            'published' => '1910'
        ]);

        $this->expectException(ValidationException::class);
        $book->update([
            'published' => '2015'               // this should fail saving rules
        ]);
    }
}
