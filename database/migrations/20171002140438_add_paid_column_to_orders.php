<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPaidColumnToOrders extends Migration
{
    public function up()
    {
        $this->schema->table('orders', function (Blueprint $table) {
            $table->boolean('paid');
        });
    }

    public function down()
    {
        $this->schema->table('orders', function (Blueprint $table) {
            $table->dropColumn('paid');
        });
    }
}
