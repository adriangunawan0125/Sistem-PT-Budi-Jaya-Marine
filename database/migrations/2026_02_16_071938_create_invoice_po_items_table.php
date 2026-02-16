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
       Schema::create('invoice_po_items', function (Blueprint $table) {
    $table->id();

    $table->foreignId('invoice_po_id')
          ->constrained('invoice_po')
          ->onDelete('cascade');

    $table->text('description');
    $table->decimal('qty',15,2);
    $table->string('unit')->nullable();
    $table->decimal('price',15,2);
    $table->decimal('amount',15,2);

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_po_items');
    }
};
