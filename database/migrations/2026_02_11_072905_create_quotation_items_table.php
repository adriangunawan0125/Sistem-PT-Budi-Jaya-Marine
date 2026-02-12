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
    Schema::create('quotation_items', function (Blueprint $table) {
        $table->id();

        $table->foreignId('sub_item_id')
              ->constrained('quotation_sub_items')
              ->cascadeOnDelete();

        $table->string('item');
        $table->decimal('price', 15, 2)->default(0);
        $table->decimal('qty', 15, 2)->default(1);
        $table->string('unit')->nullable();

        $table->decimal('day', 10, 2)->nullable();
        $table->decimal('hour', 10, 2)->nullable();

        $table->decimal('total', 15, 2)->default(0);

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};
