<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluaran_po', function (Blueprint $table) {
            $table->id();

            $table->foreignId('po_masuk_id')
                  ->constrained('po_masuk')
                  ->onDelete('cascade');

            $table->string('item');
            $table->decimal('qty', 10, 2)->default(1);
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_po');
    }
};

