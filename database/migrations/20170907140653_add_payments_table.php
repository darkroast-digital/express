<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPaymentsTable extends Migration
{
    public function up()
    {
        $this->schema->create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->tinyInteger('failed');
            $table->string('transaction_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('payments');
    }
}
