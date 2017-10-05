<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCategoryToProductsTable extends Migration
{
    public function up()
    {
        $this->schema->table('products', function (Blueprint $table) {
            $table->string('category');
        });
    }

    public function down()
    {
        $this->schema->table('products', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
}
