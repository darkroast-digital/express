<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTimeToProductsTable extends Migration
{
    public function up()
    {
        $this->schema->table('products', function (Blueprint $table) {
            $table->integer('time');
        });
    }

    public function down()
    {
       $this->schema->table('products', function (Blueprint $table) {
            $table->dropColumn('time');
        });
    }
}
