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
        Schema::create('po_masuk_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('po_masuk_id')->constrained('po_masuk')->cascadeOnDelete();
    $table->string('item');
    $table->decimal('price_jual', 15, 2);
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
        Schema::dropIfExists('po_masuk_items');
    }
};
