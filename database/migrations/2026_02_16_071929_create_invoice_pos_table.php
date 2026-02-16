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
        Schema::create('invoice_po', function (Blueprint $table) {
    $table->id();

    $table->foreignId('po_masuk_id')
          ->constrained('po_masuk')
          ->onDelete('cascade');

    $table->string('no_invoice');
    $table->date('tanggal_invoice');

    $table->string('authorization_no')->nullable();
    $table->string('periode');
    $table->string('manpower')->nullable();

    $table->enum('discount_type',['percent','nominal'])->nullable();
    $table->decimal('discount_value',15,2)->nullable();
    $table->decimal('discount_amount',15,2)->default(0);

    $table->decimal('subtotal',15,2)->default(0);
    $table->decimal('grand_total',15,2)->default(0);

    $table->enum('status',['draft','issued','paid','cancelled'])
          ->default('draft');

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_pos');
    }
};
