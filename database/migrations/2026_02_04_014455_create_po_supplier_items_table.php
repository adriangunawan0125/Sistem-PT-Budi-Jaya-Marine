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
        Schema::create('po_supplier_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('po_supplier_id')->constrained('po_supplier')->cascadeOnDelete();
    $table->string('item');
    $table->decimal('price_beli', 15, 2);
    $table->integer('qty');
    $table->string('unit')->nullable();
    $table->decimal('amount', 15, 2);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_supplier_items');
    }
};
