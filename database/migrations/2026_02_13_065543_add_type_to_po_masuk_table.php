<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::table('po_masuk', function (Blueprint $table) {
    $table->enum('type',['sparepart','manpower'])
          ->default('sparepart')
          ->after('no_po_klien');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('po_masuk', function (Blueprint $table) {
            //
        });
    }
};
