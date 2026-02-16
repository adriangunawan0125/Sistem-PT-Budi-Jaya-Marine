<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('po_supplier_terms', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('po_supplier_id');
            $table->text('description');

            $table->timestamps();

            $table->foreign('po_supplier_id')
                ->references('id')
                ->on('po_supplier')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('po_supplier_terms');
    }
};

