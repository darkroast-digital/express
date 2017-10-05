<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddProductsTable extends Migration
{
    public function up()
    {
        $this->schema->create('products', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('slug');
            $table->integer('price');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('products');
    }
}
