<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soa_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('soa_id')
                ->constrained('soas')
                ->onDelete('cascade');

            $table->foreignId('invoice_po_id')
                ->constrained('invoice_po')
                ->onDelete('cascade');

            $table->date('acceptment_date')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soa_items');
    }
};
