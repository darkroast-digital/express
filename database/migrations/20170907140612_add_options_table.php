<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddOptionsTable extends Migration
{
    public function up()
    {
        $this->schema->create('options', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->string('name');
            $table->integer('price');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('options');
    }
}
