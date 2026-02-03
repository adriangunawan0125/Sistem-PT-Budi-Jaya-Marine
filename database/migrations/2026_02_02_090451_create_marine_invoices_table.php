<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marine_invoices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                  ->constrained('companies')
                  ->cascadeOnDelete();

            $table->date('invoice_date');

            // simpan bulan saja (contoh: 2026-01-01)
            $table->date('period');

            $table->string('authorization_no');
            $table->string('vessel')->nullable();
            $table->string('po_no')->nullable();

            $table->integer('manpower')->nullable();

            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('dp', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marine_invoices');
    }
};
