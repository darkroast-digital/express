<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddProductImagesTable extends Migration
{
    public function up()
    {
        $this->schema->create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->string('iamge');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('images');
    }
}
