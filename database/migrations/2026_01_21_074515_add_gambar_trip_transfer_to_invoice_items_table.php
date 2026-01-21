<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->string('gambar_trip')->nullable()->after('amount');
            $table->string('gambar_transfer')->nullable()->after('gambar_trip');
        });
    }

    public function down()
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['gambar_trip', 'gambar_transfer']);
        });
    }
};
