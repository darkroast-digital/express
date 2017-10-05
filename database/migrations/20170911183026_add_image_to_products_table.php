<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddImageToProductsTable extends Migration
{
    public function up()
    {
        $this->schema->table('products', function (Blueprint $table) {
            $table->string('image');
        });
    }

    public function down()
    {
        $this->schema->table('products', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
}
