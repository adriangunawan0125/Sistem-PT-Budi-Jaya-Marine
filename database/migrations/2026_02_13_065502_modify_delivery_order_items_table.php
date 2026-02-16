<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('delivery_order_items', function (Blueprint $table) {

            // 🔥 DROP FOREIGN KEY DULU
            $table->dropForeign(['po_supplier_item_id']);

            // 🔥 BARU DROP COLUMN
            $table->dropColumn('po_supplier_item_id');

        });
    }

    public function down(): void
    {
        Schema::table('delivery_order_items', function (Blueprint $table) {

            $table->unsignedBigInteger('po_supplier_item_id');

            $table->foreign('po_supplier_item_id')
                  ->references('id')
                  ->on('po_supplier_items')
                  ->onDelete('cascade');

        });
    }
};
