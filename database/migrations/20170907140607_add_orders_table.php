<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddOrdersTable extends Migration
{
    public function up()
    {
        $this->schema->create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('hash');
            $table->float('total');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('orders');
    }
}
