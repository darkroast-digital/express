<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddMoreDetailsToDetailsCauseThisIsStupid extends Migration
{
    public function up()
    {
        $this->schema->table('details', function (Blueprint $table) {
            $table->string('six');
            $table->string('seven');
            $table->string('eight');
            $table->string('nine');
            $table->string('ten');
        });
    }

    public function down()
    {

        $this->schema->table('details', function (Blueprint $table) {
            $table->dropColumn('six');
            $table->dropColumn('seven');
            $table->dropColumn('eight');
            $table->dropColumn('nine');
            $table->dropColumn('ten');
        });
    }
}
