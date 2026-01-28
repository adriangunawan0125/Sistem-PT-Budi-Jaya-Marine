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
        $table->boolean('is_checked')->default(0);
    });
}

public function down()
{
    Schema::table('calonmitra', function (Blueprint $table) {
        $table->dropColumn('is_checked');
    });
}

};
