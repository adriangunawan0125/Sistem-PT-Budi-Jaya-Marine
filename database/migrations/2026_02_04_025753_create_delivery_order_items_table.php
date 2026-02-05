<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('delivery_order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('delivery_order_id')
                  ->constrained('delivery_orders')
                  ->cascadeOnDelete();

            $table->foreignId('po_supplier_item_id')
                  ->constrained('po_supplier_items')
                  ->restrictOnDelete();

            $table->string('item');
            $table->decimal('qty', 10, 2);
            $table->string('unit');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_order_items');
    }
};
