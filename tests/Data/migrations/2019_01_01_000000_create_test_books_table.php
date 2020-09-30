<?php

use Illuminate\Database\Migrations\Migration;

class CreateTestBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_books', function ($table) {
            $table->increments('id');
            $table->string('author');
            $table->string('title');
            $table->smallInteger('published');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('test_books');
    }
}