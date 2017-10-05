<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddProductDetailsTable extends Migration
{
    public function up()
    {
        $this->schema->create('details', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->string('one');
            $table->string('two');
            $table->string('three');
            $table->string('four');
            $table->string('five');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('details');
    }
}
