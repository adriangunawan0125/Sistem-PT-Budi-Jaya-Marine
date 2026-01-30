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
    Schema::table('calonmitra', function (Blueprint $table) {
        $table->string('jaminan')->after('alamat');
        $table->string('gambar_1')->nullable()->after('jaminan');
        $table->string('gambar_2')->nullable()->after('gambar_1');
        $table->string('gambar_3')->nullable()->after('gambar_2');
    });
}

public function down()
{
    Schema::table('calonmitra', function (Blueprint $table) {
        $table->dropColumn(['jaminan', 'gambar_1', 'gambar_2', 'gambar_3']);
    });
}

};
