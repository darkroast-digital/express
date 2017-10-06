<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIsPrintableToProductsTable extends Migration
{
    public function up()
    {
        $this->schema->table('products', function (Blueprint $table) {
            $table->boolean('printable')->default(0);
        });
    }

    public function down()
    {
        $this->schema->table('products', function (Blueprint $table) {
            $table->dropColumn('is-printable');
        });
    }
}
