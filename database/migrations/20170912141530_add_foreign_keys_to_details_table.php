<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDetailsTable extends Migration
{
    public function up()
    {
        $this->schema->table('details', function (Blueprint $table) {
            $table->foreign('product_id')
              ->references('id')->on('products')
              ->onDelete('cascade');
        });
    }

    public function down()
    {
        //
    }
}
