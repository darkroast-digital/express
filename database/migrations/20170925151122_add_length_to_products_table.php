<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddLengthToProductsTable extends Migration
{
    public function up()
    {
        $this->schema->table('products', function (Blueprint $table) {
            $table->string('length')->default('hours');
        });
    }

    public function down()
    {
        $this->schema->table('products', function (Blueprint $table) {
            $table->dropColumn('length');
        });
    }
}
