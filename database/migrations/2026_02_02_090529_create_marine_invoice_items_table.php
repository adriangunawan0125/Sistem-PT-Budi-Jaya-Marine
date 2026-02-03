<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marine_invoice_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('marine_invoice_id')
                  ->constrained('marine_invoices')
                  ->cascadeOnDelete();

            $table->string('description');
            $table->integer('qty');
            $table->string('unit')->nullable();
            $table->decimal('price', 15, 2);
            $table->decimal('amount', 15, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marine_invoice_items');
    }
};
