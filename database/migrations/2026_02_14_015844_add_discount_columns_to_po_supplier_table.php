<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('po_supplier', function (Blueprint $table) {

            // jenis diskon (percent / nominal)
            $table->enum('discount_type', ['percent','nominal'])
                  ->nullable()
                  ->after('total_beli');

            // nilai input diskon (misal 5 atau 2000000)
            $table->decimal('discount_value', 15, 2)
                  ->nullable()
                  ->after('discount_type');

            // hasil perhitungan diskon dalam nominal rupiah
            $table->decimal('discount_amount', 15, 2)
                  ->default(0)
                  ->after('discount_value');

            // total akhir setelah diskon
            $table->decimal('grand_total', 15, 2)
                  ->default(0)
                  ->after('discount_amount');
        });
    }

    public function down(): void
    {
        Schema::table('po_supplier', function (Blueprint $table) {
            $table->dropColumn([
                'discount_type',
                'discount_value',
                'discount_amount',
                'grand_total'
            ]);
        });
    }
};

