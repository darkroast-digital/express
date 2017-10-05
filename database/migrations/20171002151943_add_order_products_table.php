<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddOrderProductsTable extends Migration
{
    public function up()
    {
        $this->schema->create('orders_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id');
            $table->string('product_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('orders_products');
    }
}
