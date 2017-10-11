<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddDisplayPrice extends Migration
{
    public function up()
    {
        $this->schema->table('products', function (Blueprint $table) {
            $table->integer('display-price');
        });
    }

    public function down()
    {
        $this->schema->table('products', function (Blueprint $table) {
            $table->dropColumn('display-price');
        });
    }
}
