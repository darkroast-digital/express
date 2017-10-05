<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSlugToOptions extends Migration
{
    public function up()
    {
        $this->schema->table('options', function (Blueprint $table) {
            $table->string('slug');
        });
    }

    public function down()
    {
        $this->schema->table('options', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
