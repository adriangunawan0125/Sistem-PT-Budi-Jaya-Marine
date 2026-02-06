<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {

            // rename tanggal -> tanggal_tf
            $table->renameColumn('tanggal', 'tanggal_tf');

            // kolom baru
            $table->string('no_invoices')->nullable()->after('invoice_id');
            $table->date('tanggal_invoices')->nullable()->after('no_invoices');
        });
    }

    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {

            $table->renameColumn('tanggal_tf', 'tanggal');

            $table->dropColumn([
                'no_invoices',
                'tanggal_invoices'
            ]);
        });
    }
};
