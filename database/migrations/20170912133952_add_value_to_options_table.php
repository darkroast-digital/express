<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddValueToOptionsTable extends Migration
{
    public function up()
    {
        $this->schema->table('options', function (Blueprint $table) {
            $table->string('value');
        });
    }

    public function down()
    {
        $this->schema->table('options', function (Blueprint $table) {
            $table->dropColumn('value');
        });
    }
}
