<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeyToOptionsTable extends Migration
{
    public function up()
    {
        $this->schema->table('options', function (Blueprint $table) {
            $table->foreign('product_id')
              ->references('id')->on('products')
              ->onDelete('cascade');
        });
    }

    public function down()
    {
        $this->schema->table('options', function (Blueprint $table) {
            //
        });
    }
}
