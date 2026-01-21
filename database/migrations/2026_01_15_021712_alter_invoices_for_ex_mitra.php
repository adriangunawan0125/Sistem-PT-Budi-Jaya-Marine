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
    Schema::table('invoices', function (Blueprint $table) {
        $table->unsignedBigInteger('mitra_id')->nullable()->change();
        $table->unsignedBigInteger('ex_mitra_id')->nullable()->after('mitra_id');
    });
}

public function down()
{
    Schema::table('invoices', function (Blueprint $table) {
        $table->unsignedBigInteger('mitra_id')->nullable(false)->change();
        $table->dropColumn('ex_mitra_id');
    });
}

};
