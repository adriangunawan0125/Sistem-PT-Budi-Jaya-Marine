<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('units', function (Blueprint $table) {
        $table->string('merek')->after('nama_unit');
    });
}

public function down()
{
    Schema::table('units', function (Blueprint $table) {
        $table->dropColumn('merek');
    });
}

};
